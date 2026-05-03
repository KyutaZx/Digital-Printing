package usecase

import (
	"context"

	"golang-api/internal/delivery/websocket"
	"golang-api/internal/domain/audit"
	"golang-api/internal/domain/production"
)

type ProductionUsecase struct {
	repo      production.Repository
	auditRepo audit.Repository
}

func NewProductionUsecase(repo production.Repository, auditRepo audit.Repository) *ProductionUsecase {
	return &ProductionUsecase{
		repo:      repo,
		auditRepo: auditRepo,
	}
}

// =========================================================================
// START PRODUCTION
// =========================================================================
func (u *ProductionUsecase) StartProduction(ctx context.Context, orderID int, staffID int, notes, ip, ua string) error {
	// 1. Jalankan proses transaksi di database
	err := u.repo.StartProduction(ctx, orderID, staffID, notes)
	if err != nil {
		return err
	}

	// 2. Catat ke Audit Log
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    staffID,
		Role:      "staff",
		Action:    audit.ActionStartProduction,
		EntityType:"orders",
		EntityID:  orderID,
		IPAddress: ip,
		UserAgent: ua,
	})

	return nil
}

// =========================================================================
// FINISH PRODUCTION
// =========================================================================
func (u *ProductionUsecase) FinishProduction(ctx context.Context, orderID int, staffID int, notes, ip, ua string) error {
	// 1. Jalankan proses transaksi di database
	userID, err := u.repo.FinishProduction(ctx, orderID, staffID, notes)
	if err != nil {
		return err
	}

	// 2. Catat ke Audit Log
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    staffID,
		Role:      "staff",
		Action:    audit.ActionFinishProduction,
		EntityType:"orders",
		EntityID:  orderID,
		IPAddress: ip,
		UserAgent: ua,
	})

	// 3. Kirim Notifikasi WebSocket ke Customer
	websocket.GlobalHub.BroadcastToUser(userID, map[string]interface{}{
		"type":       "ORDER_STATUS_UPDATE",
		"order_id":   orderID,
		"new_status": "ready",
		"message":    "Yuhuu! Pesanan Anda sudah selesai dicetak dan siap diambil/dikirim.",
	})

	return nil
}
