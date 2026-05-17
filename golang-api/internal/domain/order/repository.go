package order

import "context"

type Repository interface {
	// ========================
	// BASIC
	// ========================
	// Tambahkan context.Context agar sinkron dengan pemanggilan usecase [cite: 1259 16626]
	Create(ctx context.Context, o *Order, items []OrderItem) error

	// ========================
	// MAIN FLOW
	// ========================
	Checkout(ctx context.Context, userID int) (orderID int, orderCode string, total float64, err error)

	// ========================
	// ORDER MANAGEMENT
	// ========================
	// Fungsi ini dipanggil di usecase payment, wajib pakai context [cite: 1259 16666]
	FindByID(ctx context.Context, orderID int) (*Order, error)

	// FindDetailByID mengambil detail lengkap 1 pesanan untuk ditampilkan sebagai invoice
	FindDetailByID(ctx context.Context, orderID int) (*OrderDetail, error)

	Cancel(ctx context.Context, orderID int, userID int) error

	// Baris 86 di usecase kamu akan sembuh setelah menambahkan ctx di sini [cite: 1259 16666]
	UpdateStatus(ctx context.Context, orderID int, status string, changedBy int, notes string) error

	// ========================
	// QUERY FOR CUSTOMER & OWNER
	// ========================
	// GetOrdersByUserID mengembalikan semua pesanan milik satu customer
	GetOrdersByUserID(ctx context.Context, userID int) ([]Order, error)

	// GetAllOrders mengembalikan semua pesanan (untuk dashboard owner/admin) dengan pagination
	GetAllOrders(ctx context.Context, limit int, offset int) ([]Order, error)

	// CompleteOrder menandai pesanan selesai (hanya jika statusnya ready)
	CompleteOrder(ctx context.Context, orderID int, userID int) error

	// FindUnpaidOrdersOlderThan mencari ID pesanan yang sudah lebih dari batas waktu (contoh interval: '24 HOURS')
	FindUnpaidOrdersOlderThan(ctx context.Context, duration string) ([]int, error)
}
