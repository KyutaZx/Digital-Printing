package handler

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"mime/multipart"
	"net/http"
	"os"
	"path/filepath"
	"strconv"
	"strings"
	"time"

	"golang-api/internal/usecase"

	"github.com/gin-gonic/gin"
)

type PaymentHandler struct {
	usecase *usecase.PaymentUsecase
}

func NewPaymentHandler(u *usecase.PaymentUsecase) *PaymentHandler {
	return &PaymentHandler{usecase: u}
}

// =========================================================================
// REQUEST STRUCT (tidak dipakai lagi untuk Upload karena form-data, tapi dibiarkan sbg dokumentasi)
// =========================================================================
type UploadPaymentRequest struct {
	OrderID         int     `json:"order_id" binding:"required"`
	MethodID        int     `json:"payment_method_id" binding:"required"`
	TransactionCode string  `json:"transaction_code"`
	Amount          float64 `json:"amount" binding:"required"`
	Proof           string  `json:"payment_proof" binding:"required"`
}

// =========================================================================
// UPLOAD PAYMENT (CUSTOMER)
// =========================================================================
func (h *PaymentHandler) Upload(c *gin.Context) {
	// Ambil data dari form-data
	orderIDStr := c.PostForm("order_id")
	methodIDStr := c.PostForm("payment_method_id")
	amountStr := c.PostForm("amount")
	transactionCode := c.PostForm("transaction_code")

	// Konversi tipe data
	orderID, err := strconv.Atoi(orderIDStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "order_id harus berupa angka valid"})
		return
	}

	methodID, err := strconv.Atoi(methodIDStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "payment_method_id harus berupa angka valid"})
		return
	}

	amount, err := strconv.ParseFloat(amountStr, 64)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "amount harus berupa angka valid"})
		return
	}

	// Tangani upload file bukti pembayaran
	file, err := c.FormFile("payment_proof")
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "File bukti pembayaran (payment_proof) tidak ditemukan pada form-data"})
		return
	}

	// Validasi Ekstensi File
	ext := strings.ToLower(filepath.Ext(file.Filename))
	allowedExtensions := map[string]bool{
		".jpg":  true,
		".jpeg": true,
		".png":  true,
		".pdf":  true,
	}
	if !allowedExtensions[ext] {
		c.JSON(http.StatusBadRequest, gin.H{
			"error": fmt.Sprintf("Tipe file '%s' tidak diizinkan. Gunakan: jpg, jpeg, png, atau pdf", ext),
		})
		return
	}

	// Validasi Ukuran File (Max 10MB)
	if file.Size > 10*1024*1024 {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Ukuran file terlalu besar, maksimal 10MB"})
		return
	}

	// Pastikan direktori ada
	dir := filepath.Join("uploads", "payments")
	os.MkdirAll(dir, os.ModePerm)

	// Simpan file
	filename := fmt.Sprintf("%d_%s", time.Now().Unix(), filepath.Base(file.Filename))
	savePath := filepath.Join(dir, filename)
	dbPath := "/uploads/payments/" + filename

	// ==========================================
	// 🔥 INTEGRASI PYTHON AI (BLUR DETECTION) 🔥
	// ==========================================
	if ext == ".jpg" || ext == ".jpeg" || ext == ".png" {
		src, err := file.Open()
		if err == nil {
			body := &bytes.Buffer{}
			writer := multipart.NewWriter(body)
			part, err := writer.CreateFormFile("file", file.Filename)
			
			if err == nil {
				io.Copy(part, src)
				writer.Close()

				aiServiceURL := os.Getenv("AI_SERVICE_URL")
				if aiServiceURL == "" {
					aiServiceURL = "http://localhost:5000"
				}
				req, err := http.NewRequest("POST", aiServiceURL+"/predict-blur", body)
				if err == nil {
					req.Header.Set("Content-Type", writer.FormDataContentType())
					
					// Timeout 10 detik agar tidak membuat user menunggu lama jika AI mati
					client := &http.Client{Timeout: 10 * time.Second}
					resp, err := client.Do(req)
					if err == nil {
						defer resp.Body.Close()
						var aiRes map[string]interface{}
						if json.NewDecoder(resp.Body).Decode(&aiRes) == nil {
							if status, ok := aiRes["status"].(string); ok && status == "blur" {
								src.Close() // Pastikan file ditutup sebelum return
								c.JSON(http.StatusBadRequest, gin.H{
									"error": "Mohon maaf, AI kami mendeteksi gambar bukti pembayaran Anda terlalu pecah/blur. Silakan unggah gambar yang lebih jelas agar mudah diverifikasi.",
								})
								return
							}
						}
					} else {
						fmt.Println("WARNING: AI Service Unreachable. Bypassing image check...")
					}
				}
			}
			src.Close()
		}
	}
	// ==========================================

	if err := c.SaveUploadedFile(file, savePath); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan file: " + err.Error()})
		return
	}

	// Ambil data dari context (JWT Middleware)
	userID := c.MustGet("user_id").(int)

	// Tangkap metadata untuk Audit Log
	ip := c.ClientIP()
	ua := c.Request.UserAgent()

	// Eksekusi usecase
	paymentID, err := h.usecase.UploadProof(
		c.Request.Context(),
		userID,
		orderID,
		methodID,
		transactionCode,
		amount,
		dbPath,
		ip,
		ua,
	)

	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message":    "Bukti pembayaran berhasil diunggah",
		"payment_id": paymentID,
	})
}

// =========================================================================
// APPROVE PAYMENT (OWNER/ADMIN)
// =========================================================================
func (h *PaymentHandler) Approve(c *gin.Context) {
	adminID := c.MustGet("user_id").(int)
	ip := c.ClientIP()
	ua := c.Request.UserAgent()

	idStr := c.Param("id")
	paymentID, err := strconv.Atoi(idStr)
	if err != nil || paymentID <= 0 {
		c.JSON(http.StatusBadRequest, gin.H{"message": "ID pembayaran tidak valid"})
		return
	}

	err = h.usecase.Approve(c.Request.Context(), paymentID, adminID, ip, ua)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Pembayaran berhasil disetujui"})
}

// =========================================================================
// REJECT PAYMENT (OWNER/ADMIN)
// =========================================================================
func (h *PaymentHandler) Reject(c *gin.Context) {
	adminID := c.MustGet("user_id").(int)
	ip := c.ClientIP()
	ua := c.Request.UserAgent()

	idStr := c.Param("id")
	paymentID, err := strconv.Atoi(idStr)
	if err != nil || paymentID <= 0 {
		c.JSON(http.StatusBadRequest, gin.H{"message": "ID pembayaran tidak valid"})
		return
	}

	err = h.usecase.Reject(c.Request.Context(), paymentID, adminID, ip, ua)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Pembayaran berhasil ditolak"})
}
