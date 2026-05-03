package handler

import (
	"bytes"
	"fmt"
	"net/http"
	"strconv"

	"golang-api/internal/domain/order"
	"golang-api/internal/usecase"

	"github.com/gin-gonic/gin"
	"github.com/go-pdf/fpdf"
)

type OrderHandler struct {
	usecase *usecase.OrderUsecase
}

func NewOrderHandler(u *usecase.OrderUsecase) *OrderHandler {
	return &OrderHandler{u}
}

// =========================================================================
// CREATE (OPTIONAL/MANUAL)
// =========================================================================
type OrderItemRequest struct {
	ProductID int    `json:"product_id" binding:"required"`
	VariantID int    `json:"variant_id" binding:"required"`
	Quantity  int    `json:"quantity" binding:"required"`
	Notes     string `json:"notes"`
}

type CreateOrderRequest struct {
	Items []OrderItemRequest `json:"items" binding:"required,dive"`
}

func (h *OrderHandler) Create(c *gin.Context) {
	var req CreateOrderRequest

	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "Format request tidak valid"})
		return
	}

	// Ambil userID dari JWT Middleware
	userID := c.MustGet("user_id").(int)

	// Tarik Metadata untuk Audit Log
	ip := c.ClientIP()
	ua := c.Request.UserAgent()

	// Map DTO to Domain
	var items []order.OrderItem
	for _, item := range req.Items {
		items = append(items, order.OrderItem{
			ProductID: item.ProductID,
			VariantID: item.VariantID,
			Quantity:  item.Quantity,
			Notes:     item.Notes,
		})
	}

	// Kirim context dan metadata ke usecase
	err := h.usecase.Create(c.Request.Context(), userID, items, ip, ua)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Pesanan berhasil dibuat secara manual"})
}

// =========================================================================
// CHECKOUT (PROSES UTAMA)
// =========================================================================
func (h *OrderHandler) Checkout(c *gin.Context) {
	userID := c.MustGet("user_id").(int)

	// Tarik Metadata untuk Audit Log
	ip := c.ClientIP()
	ua := c.Request.UserAgent()

	// Teruskan context dan metadata ke usecase untuk memproses transaksi DB
	orderID, orderCode, total, err := h.usecase.Checkout(c.Request.Context(), userID, ip, ua)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message":     "Checkout berhasil",
		"order_id":    orderID,
		"order_code":  orderCode,
		"total_price": total,
	})
}

// =========================================================================
// CANCEL ORDER
// =========================================================================
func (h *OrderHandler) Cancel(c *gin.Context) {
	// Pastikan hanya customer yang bisa cancel pesanan mereka sendiri
	role := c.MustGet("role").(string)
	if role != "customer" {
		c.JSON(http.StatusForbidden, gin.H{"message": "Hanya pelanggan yang dapat membatalkan pesanan"})
		return
	}

	// Ambil ID pesanan dari URL parameter
	orderIDStr := c.Param("id")
	orderID, err := strconv.Atoi(orderIDStr)
	if err != nil || orderID <= 0 {
		c.JSON(http.StatusBadRequest, gin.H{"message": "ID pesanan tidak valid"})
		return
	}

	userID := c.MustGet("user_id").(int)

	// Tarik Metadata untuk Audit Log
	ip := c.ClientIP()
	ua := c.Request.UserAgent()

	// Panggil usecase dengan Context dan metadata
	err = h.usecase.Cancel(c.Request.Context(), orderID, userID, ip, ua)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Pesanan berhasil dibatalkan"})
}

// =========================================================================
// GET MY ORDERS (Customer — melihat daftar pesanannya sendiri)
// =========================================================================
func (h *OrderHandler) GetMyOrders(c *gin.Context) {
	userID := c.MustGet("user_id").(int)

	orders, err := h.usecase.GetMyOrders(c.Request.Context(), userID)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Daftar pesanan Anda",
		"total":   len(orders),
		"data":    orders,
	})
}

// =========================================================================
// GET ORDER DETAIL / INVOICE
// =========================================================================
func (h *OrderHandler) GetOrderDetail(c *gin.Context) {
	orderIDStr := c.Param("id")
	orderID, err := strconv.Atoi(orderIDStr)
	if err != nil || orderID <= 0 {
		c.JSON(http.StatusBadRequest, gin.H{"message": "ID pesanan tidak valid"})
		return
	}

	userID := c.MustGet("user_id").(int)
	role := c.MustGet("role").(string)

	detail, err := h.usecase.GetOrderDetail(c.Request.Context(), orderID, userID, role)
	if err != nil {
		if err.Error() == "akses ditolak: pesanan ini bukan milik Anda" {
			c.JSON(http.StatusForbidden, gin.H{"message": err.Error()})
			return
		}
		if err.Error() == "pesanan tidak ditemukan" {
			c.JSON(http.StatusNotFound, gin.H{"message": err.Error()})
			return
		}
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Detail pesanan",
		"data":    detail,
	})
}

// =========================================================================
// GET ALL ORDERS (Owner/Admin Dashboard)
// =========================================================================
func (h *OrderHandler) GetAllOrders(c *gin.Context) {
	pageStr := c.DefaultQuery("page", "1")
	limitStr := c.DefaultQuery("limit", "10")

	page, _ := strconv.Atoi(pageStr)
	limit, _ := strconv.Atoi(limitStr)

	if page < 1 {
		page = 1
	}
	if limit < 1 {
		limit = 10
	}
	
	offset := (page - 1) * limit

	orders, err := h.usecase.GetAllOrders(c.Request.Context(), limit, offset)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "Semua pesanan",
		"page":    page,
		"limit":   limit,
		"total":   len(orders),
		"data":    orders,
	})
}

// =========================================================================
// COMPLETE ORDER (Customer mengonfirmasi barang telah diterima)
// =========================================================================
func (h *OrderHandler) CompleteOrder(c *gin.Context) {
	userID := c.MustGet("user_id").(int)
	orderIDStr := c.Param("id")
	orderID, err := strconv.Atoi(orderIDStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "ID pesanan tidak valid"})
		return
	}

	ip := c.ClientIP()
	ua := c.GetHeader("User-Agent")

	err = h.usecase.CompleteOrder(c.Request.Context(), orderID, userID, ip, ua)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Pesanan berhasil diselesaikan. Terima kasih!"})
}

// =========================================================================
// DOWNLOAD INVOICE PDF
// =========================================================================
func (h *OrderHandler) DownloadInvoicePDF(c *gin.Context) {
	orderIDStr := c.Param("id")
	orderID, err := strconv.Atoi(orderIDStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "ID pesanan tidak valid"})
		return
	}

	userID := c.MustGet("user_id").(int)
	role := c.MustGet("role").(string)

	// Ambil detail pesanan
	o, err := h.usecase.GetOrderDetail(c.Request.Context(), orderID, userID, role)
	if err != nil {
		c.JSON(http.StatusNotFound, gin.H{"message": err.Error()})
		return
	}

	// Buat Dokumen PDF
	pdf := fpdf.New("P", "mm", "A4", "")
	pdf.AddPage()
	pdf.SetFont("Arial", "B", 16)
	pdf.Cell(40, 10, "INVOICE DIGITAL PRINTING")
	pdf.Ln(10)

	pdf.SetFont("Arial", "", 12)
	pdf.Cell(40, 10, fmt.Sprintf("Kode Pesanan: %s", o.OrderCode))
	pdf.Ln(8)
	pdf.Cell(40, 10, fmt.Sprintf("Status: %s", o.Status))
	pdf.Ln(8)
	pdf.Cell(40, 10, fmt.Sprintf("Total Harga: Rp %.2f", o.TotalPrice))
	pdf.Ln(15)

	// Header Tabel
	pdf.SetFont("Arial", "B", 12)
	pdf.CellFormat(80, 10, "Nama Produk", "1", 0, "C", false, 0, "")
	pdf.CellFormat(30, 10, "Qty", "1", 0, "C", false, 0, "")
	pdf.CellFormat(40, 10, "Harga", "1", 0, "C", false, 0, "")
	pdf.Ln(-1)

	// Isi Tabel
	pdf.SetFont("Arial", "", 12)
	for _, item := range o.Items {
		pdf.CellFormat(80, 10, item.ProductName, "1", 0, "L", false, 0, "")
		pdf.CellFormat(30, 10, fmt.Sprintf("%d", item.Quantity), "1", 0, "C", false, 0, "")
		pdf.CellFormat(40, 10, fmt.Sprintf("Rp %.2f", item.UnitPrice), "1", 0, "R", false, 0, "")
		pdf.Ln(-1)
	}

	// Tulis PDF ke buffer memory
	var buf bytes.Buffer
	if err := pdf.Output(&buf); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Gagal membuat PDF"})
		return
	}

	// Kirim langsung sebagai file download
	c.Header("Content-Disposition", fmt.Sprintf("attachment; filename=Invoice_%s.pdf", o.OrderCode))
	c.Data(http.StatusOK, "application/pdf", buf.Bytes())
}
