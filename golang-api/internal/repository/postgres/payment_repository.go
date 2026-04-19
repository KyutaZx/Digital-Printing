package postgres

import (
	"context"
	"database/sql"
	"errors"

	"golang-api/internal/domain/payment"
)

type paymentRepository struct {
	db *sql.DB
}

// NewPaymentRepository menginisialisasi repository payment dengan koneksi DB
func NewPaymentRepository(db *sql.DB) payment.Repository {
	return &paymentRepository{db: db}
}

// =========================================================================
// CREATE PAYMENT
// =========================================================================
func (r *paymentRepository) Create(ctx context.Context, p *payment.Payment) error {
	// Sesuai skema: order_id, payment_method_id, transaction_code, amount, proof, status [cite: 1259 16710]
	query := `
		INSERT INTO payment_transactions 
		(order_id, payment_method_id, transaction_code, amount, payment_proof, payment_status, created_at)
		VALUES ($1, $2, $3, $4, $5, $6, NOW())
		RETURNING id
	`
	err := r.db.QueryRowContext(ctx, query,
		p.OrderID,
		p.MethodID,
		p.TransactionCode,
		p.Amount,
		p.Proof,
		p.Status,
	).Scan(&p.ID)

	return err
}

// =========================================================================
// UPDATE STATUS (TRANSACTIONAL)
// =========================================================================
func (r *paymentRepository) UpdateStatus(ctx context.Context, id int, status string, verifiedBy int, orderID int, orderStatus string) error {
	// Memulai Transaksi Database agar tabel Payment dan Order terupdate bersamaan [cite: 1259 16666, 16710]
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return err
	}

	// 1. Update status pembayaran [cite: 1259 16710]
	queryPayment := `
		UPDATE payment_transactions
		SET payment_status = $1, 
		    verified_by = $2, 
		    verified_at = NOW()
		WHERE id = $3
	`
	res, err := tx.ExecContext(ctx, queryPayment, status, verifiedBy, id)
	if err != nil {
		tx.Rollback()
		return err
	}

	rows, _ := res.RowsAffected()
	if rows == 0 {
		tx.Rollback()
		return errors.New("transaksi pembayaran tidak ditemukan")
	}

	// 2. Update status order [cite: 1259 16666]
	queryOrder := `UPDATE orders SET status = $1, updated_at = NOW() WHERE id = $2`
	_, err = tx.ExecContext(ctx, queryOrder, orderStatus, orderID)
	if err != nil {
		tx.Rollback()
		return err
	}

	// Commit jika kedua update berhasil
	return tx.Commit()
}

// =========================================================================
// FIND BY ID
// =========================================================================
func (r *paymentRepository) FindByID(ctx context.Context, id int) (*payment.Payment, error) {
	var p payment.Payment
	query := `
		SELECT id, order_id, payment_method_id, transaction_code, amount, payment_proof, payment_status
		FROM payment_transactions
		WHERE id = $1
	`
	err := r.db.QueryRowContext(ctx, query, id).Scan(
		&p.ID,
		&p.OrderID,
		&p.MethodID,
		&p.TransactionCode,
		&p.Amount,
		&p.Proof,
		&p.Status,
	)

	if err == sql.ErrNoRows {
		return nil, nil
	}

	if err != nil {
		return nil, err
	}

	return &p, nil
}
