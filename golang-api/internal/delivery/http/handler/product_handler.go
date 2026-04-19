package handler

import (
	"net/http"

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
