package design

import "time"

// DesignReview merepresentasikan tabel design_reviews di PostgreSQL
type DesignReview struct {
	ID           int        `json:"id"`
	DesignFileID int        `json:"design_file_id"` // Menggantikan order_id yang lama
	Status       string     `json:"status"`
	ReviewNotes  string     `json:"review_notes"`
	CreatedAt    time.Time  `json:"created_at"`
	UpdatedAt    *time.Time `json:"updated_at,omitempty"`
}

// DesignFile merepresentasikan tabel design_files
type DesignFile struct {
	ID        int       `json:"id"`
	OrderID   int       `json:"order_id"` // Relasi back ke order
	FileURL   string    `json:"file_url"`
	CreatedAt time.Time `json:"created_at"`
}
