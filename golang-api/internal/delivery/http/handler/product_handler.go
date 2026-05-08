package handler

import (
	"net/http"
	"strconv"

	"golang-api/internal/domain/product"
	"golang-api/internal/usecase"

	"github.com/gin-gonic/gin"
)

type ProductHandler struct {
	usecase *usecase.ProductUsecase
}

func NewProductHandler(u *usecase.ProductUsecase) *ProductHandler {
	return &ProductHandler{u}
}

// ========================
// GET ALL PRODUCTS
// ========================
func (h *ProductHandler) GetAll(c *gin.Context) {

	products, err := h.usecase.GetAll()
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{
			"message": err.Error(),
		})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "success get products",
		"data":    products,
	})
}

// ========================
// CREATE PRODUCT
// ========================
func (h *ProductHandler) Create(c *gin.Context) {
	var req product.ProductRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "invalid request", "error": err.Error()})
		return
	}

	productID, err := h.usecase.Create(req)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusCreated, gin.H{
		"message": "success create product",
		"data":    gin.H{"id": productID},
	})
}

// ========================
// UPDATE PRODUCT
// ========================
func (h *ProductHandler) Update(c *gin.Context) {
	idStr := c.Param("id")
	id, err := strconv.Atoi(idStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "invalid product id"})
		return
	}

	var req product.ProductRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "invalid request", "error": err.Error()})
		return
	}

	if err := h.usecase.Update(id, req); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "success update product"})
}

// ========================
// DELETE PRODUCT
// ========================
func (h *ProductHandler) Delete(c *gin.Context) {
	idStr := c.Param("id")
	id, err := strconv.Atoi(idStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "invalid product id"})
		return
	}

	if err := h.usecase.Delete(id); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "success delete product"})
}

// ========================
// UPDATE PRODUCT IMAGE
// ========================
func (h *ProductHandler) UpdateImage(c *gin.Context) {
	idStr := c.Param("id")
	id, err := strconv.Atoi(idStr)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "invalid product id"})
		return
	}

	// 1. Ambil file dari request
	file, err := c.FormFile("image")
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"message": "file image required"})
		return
	}

	// 2. Generate nama file unik
	filename := strconv.Itoa(id) + "_" + file.Filename
	filepath := "uploads/products/" + filename

	// 3. Simpan ke storage local server
	if err := c.SaveUploadedFile(file, filepath); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "failed to save image", "error": err.Error()})
		return
	}

	// 4. Update path di database
	if err := h.usecase.UpdateImage(id, "/"+filepath); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": err.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"message": "success update product image",
		"image":   "/" + filepath,
	})
}
