package usecase

import (
	"context"
	"errors"
	"fmt"
	"time"

	"golang-api/internal/domain/audit"
	"golang-api/internal/domain/order"
)

type OrderUsecase struct {
	repo      order.Repository
	auditRepo audit.Repository
}

func NewOrderUsecase(repo order.Repository, auditRepo audit.Repository) *OrderUsecase {
	return &OrderUsecase{
		repo:      repo,
		auditRepo: auditRepo,
	}
}

// =========================================================================
// CREATE ORDER (MANUAL/OPTIONAL)
// =========================================================================
// =========================================================================
// CREATE ORDER (MANUAL/OPTIONAL)
// =========================================================================
func (u *OrderUsecase) Create(ctx context.Context, userID int, items []order.OrderItem, ip, ua string) error {
	if len(items) == 0 {
		return errors.New("pesanan harus memiliki setidaknya satu item")
	}

	orderCode := fmt.Sprintf("ORD-%d", time.Now().Unix())

	o := &order.Order{
		UserID:    userID,
		OrderCode: orderCode,
		Status:    "waiting_payment",
	}

	if err := u.repo.Create(ctx, o, items); err != nil {
		return err
	}

	// Catat Audit Log dengan Metadata Lengkap
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    userID,
		Role:      "customer",
		Action:    audit.ActionCreateOrder,
		Entity:    "orders",
		EntityID:  o.ID,
		IPAddress: ip,
		UserAgent: ua,
	})

	return nil
}

// =========================================================================
// CHECKOUT (PROSES UTAMA DARI KERANJANG)
// =========================================================================
func (u *OrderUsecase) Checkout(ctx context.Context, userID int, ip, ua string) (int, string, float64, error) {
	// Panggil repo checkout (Transaksi DB)
	orderID, orderCode, total, err := u.repo.Checkout(ctx, userID)
	if err != nil {
		return 0, "", 0, err
	}

	// Catat Audit Log Checkout
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    userID,
		Role:      "customer",
		Action:    audit.ActionCheckout,
		Entity:    "orders",
		EntityID:  orderID,
		IPAddress: ip,
		UserAgent: ua,
	})

	return orderID, orderCode, total, nil
}

// =========================================================================
// CANCEL ORDER
// =========================================================================
func (u *OrderUsecase) Cancel(ctx context.Context, orderID int, userID int, ip, ua string) error {
	o, err := u.repo.FindByID(ctx, orderID)
	if err != nil {
		return err
	}
	if o == nil {
		return errors.New("pesanan tidak ditemukan")
	}

	// Validasi kepemilikan
	if o.UserID != userID {
		return errors.New("anda tidak memiliki akses untuk membatalkan pesanan ini")
	}

	// Validasi status
	if o.Status != "waiting_payment" && o.Status != "payment_verification" {
		return errors.New("pesanan tidak dapat dibatalkan karena sudah dalam proses")
	}

	if err := u.repo.Cancel(ctx, orderID, userID); err != nil {
		return err
	}

	// Catat Audit Log Pembatalan
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    userID,
		Role:      "customer",
		Action:    "CANCEL_ORDER",
		Entity:    "orders",
		EntityID:  orderID,
		IPAddress: ip,
		UserAgent: ua,
	})

	return nil
}
