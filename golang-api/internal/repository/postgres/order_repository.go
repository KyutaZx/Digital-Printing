package postgres

import (
	"context"
	"database/sql"
	"fmt"
	"time"

	"golang-api/internal/domain/order"
)

type orderRepository struct {
	db *sql.DB
}

func NewOrderRepository(db *sql.DB) order.Repository {
	return &orderRepository{db: db}
}

// =========================================================================
// CREATE
// =========================================================================
func (r *orderRepository) Create(ctx context.Context, o *order.Order, items []order.OrderItem) error {
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return err
	}
	defer tx.Rollback()

	var total float64
	// 1. Validasi Variant & Hitung Total Harga murni dari DB
	for i := range items {
		var price float64
		err := tx.QueryRowContext(ctx, "SELECT price FROM product_variants WHERE id = $1", items[i].VariantID).Scan(&price)
		if err == sql.ErrNoRows {
			return fmt.Errorf("variant id %d tidak ditemukan", items[i].VariantID)
		}
		if err != nil {
			return err
		}
		items[i].Price = price
		total += price * float64(items[i].Quantity)
	}

	o.TotalPrice = total

	// 2. Simpan Header Order
	queryOrder := `
		INSERT INTO orders (user_id, order_code, total_price, status, created_at)
		VALUES ($1, $2, $3, $4, NOW())
		RETURNING id
	`
	err = tx.QueryRowContext(ctx, queryOrder, o.UserID, o.OrderCode, o.TotalPrice, o.Status).Scan(&o.ID)
	if err != nil {
		return err
	}

	// 3. Simpan Item Order
	for _, item := range items {
		queryItem := `
			INSERT INTO order_items (order_id, product_id, variant_id, quantity, price, notes)
			VALUES ($1, $2, $3, $4, $5, $6)
		`
		_, err = tx.ExecContext(ctx, queryItem, o.ID, item.ProductID, item.VariantID, item.Quantity, item.Price, item.Notes)
		if err != nil {
			return err
		}
	}

	return tx.Commit()
}

// =========================================================================
// CHECKOUT (TRANSACTION SAFE)
// =========================================================================
func (r *orderRepository) Checkout(ctx context.Context, userID int) (int, string, float64, error) {
	// Memulai Transaksi dengan Context
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return 0, "", 0, err
	}

	defer func() {
		if err != nil {
			tx.Rollback()
		}
	}()

	// 1. Ambil ID Keranjang User
	var cartID int
	err = tx.QueryRowContext(ctx, "SELECT id FROM carts WHERE user_id = $1", userID).Scan(&cartID)
	if err == sql.ErrNoRows {
		return 0, "", 0, fmt.Errorf("keranjang tidak ditemukan")
	}
	if err != nil {
		return 0, "", 0, err
	}

	// 2. Ambil Item Keranjang Beserta Detail Spesifikasi Cetak
	rows, err := tx.QueryContext(ctx, `
		SELECT ci.product_id, ci.quantity, pv.price, 
		       ci.variant_id, ci.notes
		FROM cart_items ci
		JOIN product_variants pv ON pv.id = ci.variant_id
		WHERE ci.cart_id = $1
	`, cartID)
	if err != nil {
		return 0, "", 0, err
	}
	defer rows.Close()

	type item struct {
		productID   int
		quantity    int
		price       float64
		variantID   int
		notes       string
	}

	var items []item
	var total float64

	for rows.Next() {
		var i item
		err = rows.Scan(&i.productID, &i.quantity, &i.price, &i.variantID, &i.notes)
		if err != nil {
			return 0, "", 0, err
		}
		total += i.price * float64(i.quantity)
		items = append(items, i)
	}

	if len(items) == 0 {
		return 0, "", 0, fmt.Errorf("keranjang kosong")
	}

	// 3. Buat Order Utama
	orderCode := fmt.Sprintf("ORD-%d%d", userID, time.Now().Unix())
	var orderID int
	queryOrder := `
		INSERT INTO orders (user_id, order_code, total_price, status, created_at)
		VALUES ($1, $2, $3, 'waiting_payment', NOW())
		RETURNING id
	`
	err = tx.QueryRowContext(ctx, queryOrder, userID, orderCode, total).Scan(&orderID)
	if err != nil {
		return 0, "", 0, err
	}

	// 4. Pindahkan Item Keranjang ke Order Items (Beserta Spesifikasi)
	for _, i := range items {
		queryItems := `
			INSERT INTO order_items (order_id, product_id, quantity, price, variant_id, notes)
			VALUES ($1, $2, $3, $4, $5, $6)
		`
		_, err = tx.ExecContext(ctx, queryItems,
			orderID, i.productID, i.quantity, i.price, i.variantID, i.notes,
		)
		if err != nil {
			return 0, "", 0, err
		}
	}

	// 5. Kosongkan Keranjang Setelah Checkout Berhasil
	_, err = tx.ExecContext(ctx, "DELETE FROM cart_items WHERE cart_id = $1", cartID)
	if err != nil {
		return 0, "", 0, err
	}

	// Selesaikan Transaksi
	if err = tx.Commit(); err != nil {
		return 0, "", 0, err
	}

	return orderID, orderCode, total, nil
}

// =========================================================================
// FIND BY ID
// =========================================================================
func (r *orderRepository) FindByID(ctx context.Context, orderID int) (*order.Order, error) {
	var o order.Order
	query := `SELECT id, user_id, status FROM orders WHERE id = $1`

	err := r.db.QueryRowContext(ctx, query, orderID).Scan(&o.ID, &o.UserID, &o.Status)
	if err == sql.ErrNoRows {
		return nil, nil
	}
	if err != nil {
		return nil, err
	}

	return &o, nil
}

// =========================================================================
// CANCEL ORDER
// =========================================================================
func (r *orderRepository) Cancel(ctx context.Context, orderID int, userID int) error {
	query := `UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = $1 AND user_id = $2`
	res, err := r.db.ExecContext(ctx, query, orderID, userID)
	if err != nil {
		return err
	}

	rows, _ := res.RowsAffected()
	if rows == 0 {
		return fmt.Errorf("pesanan tidak ditemukan atau akses ditolak")
	}
	return nil
}

// =========================================================================
// UPDATE STATUS
// =========================================================================
func (r *orderRepository) UpdateStatus(ctx context.Context, orderID int, status string) error {
	query := `UPDATE orders SET status = $1, updated_at = NOW() WHERE id = $2`
	res, err := r.db.ExecContext(ctx, query, status, orderID)
	if err != nil {
		return err
	}

	rows, _ := res.RowsAffected()
	if rows == 0 {
		return fmt.Errorf("pesanan tidak ditemukan")
	}
	return nil
}
