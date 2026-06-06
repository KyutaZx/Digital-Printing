package usecase

import (
	"context"
	"errors"

	"golang-api/internal/delivery/websocket"
	"golang-api/internal/domain/audit"
	"golang-api/internal/domain/order"
	"golang-api/internal/domain/payment"
)

type PaymentUsecase struct {
	repo      payment.Repository
	orderRepo order.Repository
	auditRepo audit.Repository
}

func NewPaymentUsecase(
	repo payment.Repository,
	orderRepo order.Repository,
	auditRepo audit.Repository,
) *PaymentUsecase {
	return &PaymentUsecase{
		repo:      repo,
		orderRepo: orderRepo,
		auditRepo: auditRepo,
	}
}

// =========================================================================
// UPLOAD BUKTI PEMBAYARAN (CUSTOMER)
// =========================================================================
func (u *PaymentUsecase) UploadProof(
	ctx context.Context,
	userID int,
	orderID int,
	methodID int,
	transactionCode string, // Tambahkan field ini sesuai skema DB [cite: 1259 16710]
	amount float64,
	proof string,
	ip string,
	ua string,
) (int, error) {

	// 1. Validasi Input
	if amount <= 0 {
		return 0, errors.New("jumlah pembayaran tidak valid")
	}
	if proof == "" {
		return 0, errors.New("bukti pembayaran wajib diunggah")
	}

	// 2. Cek Keberadaan Order & Validasi Status [cite: 1259 16666]
	o, err := u.orderRepo.FindByID(ctx, orderID)
	if err != nil {
		return 0, err
	}
	if o == nil {
		return 0, errors.New("pesanan tidak ditemukan")
	}

	// KEAMANAN: Pastikan order milik user yang sedang login
	if o.UserID != userID {
		return 0, errors.New("anda tidak memiliki akses ke pesanan ini")
	}

	if o.Status != "waiting_payment" && o.Status != "payment_verification" {
		return 0, errors.New("status pesanan saat ini tidak mengizinkan unggah bukti")
	}

	// Wajib upload desain untuk semua item sebelum pembayaran (kecuali re-upload setelah reject bayar)
	if o.Status == "waiting_payment" {
		ok, err := u.orderRepo.AllItemsHaveDesign(ctx, orderID)
		if err != nil {
			return 0, err
		}
		if !ok {
			return 0, errors.New("upload desain untuk semua produk terlebih dahulu sebelum melakukan pembayaran")
		}
	}

	// 3. Hapus payment lama yang sudah di-reject agar tidak terkena UNIQUE constraint
	// ketika customer mengupload ulang bukti pembayaran
	if err := u.repo.DeleteRejectedByOrderID(ctx, orderID); err != nil {
		return 0, errors.New("gagal membersihkan data pembayaran lama: " + err.Error())
	}

	// 4. Cek apakah ada payment yang masih pending
	pendingPayment, err := u.repo.GetPendingByOrderID(ctx, orderID)
	if err != nil {
		return 0, err
	}

	var paymentID int
	if pendingPayment != nil {
		// OVERWRITE pending payment
		pendingPayment.MethodID = methodID
		pendingPayment.TransactionCode = transactionCode
		pendingPayment.Amount = amount
		pendingPayment.Proof = proof
		
		if err := u.repo.Update(ctx, pendingPayment); err != nil {
			return 0, err
		}
		paymentID = pendingPayment.ID
	} else {
		// Buat Transaksi Pembayaran Baru
		p := &payment.Payment{
			OrderID:         orderID,
			MethodID:        methodID,
			TransactionCode: transactionCode,
			Amount:          amount,
			Proof:           proof,
			Status:          "pending",
		}

		if err := u.repo.Create(ctx, p); err != nil {
			return 0, err
		}
		paymentID = p.ID
	}

	// 4. Update Status Order ke Verifikasi [cite: 1259 16666]
	if err := u.orderRepo.UpdateStatus(ctx, orderID, "payment_verification", userID, "Customer uploaded payment proof"); err != nil {
		return 0, err
	}

	// 5. Catat Audit Log [cite: 1259 16531]
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    userID,
		Role:      "customer",
		Action:    audit.ActionCreatePayment,
		EntityType:"payment_transactions",
		EntityID:  paymentID,
		IPAddress: ip,
		UserAgent: ua,
	})

	return paymentID, nil
}

// =========================================================================
// APPROVE PAYMENT (ADMIN/OWNER)
// =========================================================================
func (u *PaymentUsecase) Approve(ctx context.Context, id int, adminID int, ip string, ua string) error {

	// 1. Ambil Data Pembayaran
	p, err := u.repo.FindByID(ctx, id)
	if err != nil || p == nil {
		return errors.New("data pembayaran tidak ditemukan")
	}

	if p.Status != "pending" {
		return errors.New("pembayaran sudah diproses sebelumnya")
	}

	// 2. Jalankan Update Status (Payment & Order dalam satu Transaksi DB) [cite: 1259 16710]
	// Pembayaran disetujui -> verifikasi desain (banner "lunas" di UI customer)
	err = u.repo.UpdateStatus(ctx, id, "approved", adminID, p.OrderID, "design_review")
	if err != nil {
		return err
	}

	// 3. Catat Audit Log [cite: 1259 16531]
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    adminID,
		Role:      "admin",
		Action:    audit.ActionApprovePayment,
		EntityType:"payment_transactions",
		EntityID:  id,
		IPAddress: ip,
		UserAgent: ua,
	})

	// 4. Kirim Notifikasi Real-Time (WebSocket) ke Customer
	if o, _ := u.orderRepo.FindByID(ctx, p.OrderID); o != nil {
		websocket.GlobalHub.BroadcastToUser(o.UserID, map[string]interface{}{
			"type":       "ORDER_STATUS_UPDATE",
			"order_id":   o.ID,
			"order_code": o.OrderCode,
			"new_status": "design_review",
			"message":    "Pembayaran Anda berhasil diverifikasi. Desain akan segera direview tim kami.",
		})
	}

	return nil
}

// =========================================================================
// REJECT PAYMENT (ADMIN/OWNER)
// =========================================================================
func (u *PaymentUsecase) Reject(ctx context.Context, id int, adminID int, ip string, ua string) error {

	p, err := u.repo.FindByID(ctx, id)
	if err != nil || p == nil {
		return errors.New("data pembayaran tidak ditemukan")
	}

	if p.Status != "pending" {
		return errors.New("pembayaran sudah diproses sebelumnya")
	}

	// 1. Jalankan Update Status (Payment & Order dalam satu Transaksi DB) [cite: 1259 16710]
	// Tetap di payment_verification; customer unggah ulang bukti (flag dari payment_status = rejected)
	err = u.repo.UpdateStatus(ctx, id, "rejected", adminID, p.OrderID, "payment_verification")
	if err != nil {
		return err
	}

	// 2. Catat Audit Log [cite: 1259 16531]
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:    adminID,
		Role:      "admin",
		Action:    audit.ActionRejectPayment,
		EntityType:"payment_transactions",
		EntityID:  id,
		IPAddress: ip,
		UserAgent: ua,
	})

	return nil
}
