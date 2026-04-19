package usecase

import (
	"fmt"

	"golang-api/internal/domain/product"
)

type ProductUsecase struct {
	repo product.Repository
}

func NewProductUsecase(repo product.Repository) *ProductUsecase {
	return &ProductUsecase{repo}
}

// ========================
// GET ALL PRODUCTS
// ========================
func (u *ProductUsecase) GetAll() ([]product.Product, error) {

	products, err := u.repo.FindAll()
	if err != nil {
		return nil, fmt.Errorf("failed to get products: %w", err)
	}

	return products, nil
}
