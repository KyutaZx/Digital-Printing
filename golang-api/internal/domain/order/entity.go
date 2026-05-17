package order

import "time"

// Order mencerminkan struktur tabel orders di PostgreSQL [cite: 1259 16666]
type Order struct {
	ID                  int        `json:"id"`
	UserID              int        `json:"user_id"`
	CustomerName        string     `json:"customer_name,omitempty"`
	CustomerFormattedID string     `json:"customer_formatted_id,omitempty"`
	OrderCode           string     `json:"order_code"`            // Contoh: "ORD-2026-0001" [cite: 1259 16666]
	TotalPrice          float64    `json:"total_price"`           // Sesuai numeric(12,2) [cite: 1259 16666]
	Status              string     `json:"status"`                // ENUM: waiting_payment, production, dll [cite: 1247 1247]
	EstimatedFinishDate *time.Time  `json:"estimated_finish_date"` // Bisa NULL jika belum diproses [cite: 1259 16666]
	CreatedAt           time.Time   `json:"created_at"`            // Default CURRENT_TIMESTAMP [cite: 1259 16666]
	UpdatedAt           *time.Time  `json:"updated_at,omitempty"`  // Pointer karena bisa NULL [cite: 1259 16666]
	Items               []OrderItem `json:"items,omitempty"`       // Relasi ke order_items
}

// OrderItem mencerminkan item-item individual dalam satu Order
type OrderItem struct {
	ID        int     `json:"id"`
	OrderID   int     `json:"order_id"`
	ProductID int     `json:"product_id"`
	VariantID int     `json:"variant_id"`
	Quantity  int     `json:"quantity"`
	Price     float64 `json:"price"`
	Notes     string  `json:"notes"`
}

// =========================================================================
// INVOICE / ORDER DETAIL DTO
// =========================================================================

// OrderItemDetail adalah item pesanan yang sudah dilengkapi nama produk & variant
type OrderItemDetail struct {
	ID          int     `json:"id"`
	ProductID   int     `json:"product_id"`
	ProductName string  `json:"product_name"`  // JOIN dari tabel products
	VariantID   int     `json:"variant_id"`
	VariantName string  `json:"variant_name"`  // JOIN dari tabel product_variants (nama finishing)
	SKU         string  `json:"sku"`
	Quantity    int     `json:"quantity"`
	UnitPrice   float64      `json:"unit_price"`    // Harga per satuan
	Subtotal    float64      `json:"subtotal"`      // unit_price * quantity
	Notes       string       `json:"notes"`         // Catatan cetak dari customer
	Designs     []DesignFile `json:"designs"`       // Array file desain yang diupload
}

// DesignFile merepresentasikan desain untuk order detail
type DesignFile struct {
	ID          int        `json:"id"`
	OrderItemID int        `json:"order_item_id"`
	FilePath    string     `json:"file_path"`
	Version     int        `json:"version"`
	Status      string     `json:"status"`
	UploadedAt  time.Time `json:"uploaded_at"` // Tidak boleh NULL di DB
}

// PaymentInfo adalah info pembayaran yang melekat pada invoice
type PaymentInfo struct {
	ID              int        `json:"id"`
	MethodID        int        `json:"payment_method_id"`
	TransactionCode string     `json:"transaction_code"`
	Amount          float64    `json:"amount"`
	Proof           string     `json:"payment_proof"`
	Status          string     `json:"payment_status"`
	VerifiedBy      *int       `json:"verified_by"`
	VerifiedAt      *time.Time `json:"verified_at"`
	CreatedAt       time.Time  `json:"created_at"`
}

// OrderDetail adalah response lengkap untuk endpoint invoice
type OrderDetail struct {
	// Header Pesanan
	ID                  int              `json:"id"`
	OrderCode           string           `json:"order_code"`
	Status              string           `json:"status"`
	TotalPrice          float64          `json:"total_price"`
	EstimatedFinishDate *time.Time       `json:"estimated_finish_date"`
	CreatedAt           time.Time        `json:"created_at"`
	UpdatedAt           *time.Time       `json:"updated_at,omitempty"`

	// Info Customer
	CustomerID          int    `json:"customer_id"`
	CustomerName        string `json:"customer_name"`
	CustomerFormattedID string `json:"customer_formatted_id"`
	CustomerEmail       string `json:"customer_email"`
	CustomerPhone       string `json:"customer_phone"`

	// Item Pesanan (dengan nama produk & variant)
	Items []OrderItemDetail `json:"items"`

	// Info Pembayaran (bisa nil jika belum bayar)
	Payment *PaymentInfo `json:"payment"`
}
