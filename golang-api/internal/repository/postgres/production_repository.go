package postgres

import (
	"context"
	"database/sql"
	"errors"
	"time"

	"golang-api/internal/domain/production"
)

type productionRepository struct {
	db *sql.DB
}

func NewProductionRepository(db *sql.DB) production.Repository {
	return &productionRepository{db}
}

// =========================================================================
// START PRODUCTION (Mulai Cetak)
// =========================================================================
func (r *productionRepository) StartProduction(ctx context.Context, orderID int, staffID int, notes string) error {
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return err
	}
	defer tx.Rollback()

	// 1. Update status order menjadi 'printing' (Hanya bisa jika status 'paid')
	res, err := tx.ExecContext(ctx, `
		UPDATE orders 
		SET status = 'printing', updated_at = $1 
		WHERE id = $2 AND status = 'paid'`,
		time.Now(), orderID)
	if err != nil {
		return err
	}

	affected, _ := res.RowsAffected()
	if affected == 0 {
		return errors.New("pesanan tidak ditemukan atau belum lunas")
	}

	// 2. Insert ke tabel production_logs
	_, err = tx.ExecContext(ctx, `
		INSERT INTO production_logs (order_id, staff_id, start_time, notes) 
		VALUES ($1, $2, $3, $4)`,
		orderID, staffID, time.Now(), notes)
	if err != nil {
		return err
	}

	return tx.Commit()
}

// =========================================================================
// FINISH PRODUCTION (Selesai Cetak)
// =========================================================================
func (r *productionRepository) FinishProduction(ctx context.Context, orderID int, staffID int, notes string) error {
	tx, err := r.db.BeginTx(ctx, nil)
	if err != nil {
		return err
	}
	defer tx.Rollback()

	// 1. Update status order menjadi 'ready' (Hanya bisa jika status 'printing')
	res, err := tx.ExecContext(ctx, `
		UPDATE orders 
		SET status = 'ready', updated_at = $1 
		WHERE id = $2 AND status = 'printing'`,
		time.Now(), orderID)
	if err != nil {
		return err
	}

	affected, _ := res.RowsAffected()
	if affected == 0 {
		return errors.New("pesanan tidak ditemukan atau belum dicetak")
	}

	// 2. Update waktu selesai di tabel production_logs
	_, err = tx.ExecContext(ctx, `
		UPDATE production_logs 
		SET end_time = $1, notes = CONCAT(notes, ' | ', $2::text)
		WHERE order_id = $3 AND end_time IS NULL`,
		time.Now(), notes, orderID)
	if err != nil {
		return err
	}

	return tx.Commit()
}
