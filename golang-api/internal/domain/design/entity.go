package design

import "time"

// DesignReview merepresentasikan tabel design_reviews di PostgreSQL
type DesignReview struct {
	ID           int       `json:"id"`
	ReviewedBy   int       `json:"reviewed_by"`
	Status       string    `json:"status"`
	Notes        *string   `json:"notes"`
	CreatedAt    time.Time `json:"created_at"`
	DesignFileID int       `json:"design_file_id"`
}

// DesignFile merepresentasikan tabel design_files
type DesignFile struct {
	ID          int       `json:"id"`
	OrderItemID int       `json:"order_item_id"`
	FilePath    string    `json:"file_path"`
	Version     int       `json:"version"`
	UploadedBy  int       `json:"uploaded_by"`
	CreatedAt   time.Time `json:"created_at"`
}
