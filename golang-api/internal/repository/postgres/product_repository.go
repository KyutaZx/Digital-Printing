package postgres

import (
	"database/sql"

	"golang-api/internal/domain/product"
)

type productRepository struct {
	db *sql.DB
}

func NewProductRepository(db *sql.DB) product.Repository {
	return &productRepository{db}
}

// ========================
// GET ALL ACTIVE PRODUCTS
// ========================
func (r *productRepository) FindAll() ([]product.Product, error) {

	query := `
		SELECT 
			p.id, p.name, p.description, p.base_price,
			v.id as v_id, v.sku as v_sku, v.variant_name as v_name, v.price as v_price, v.stock as v_stock, v.is_active as v_is_active, v.material_id, v.material_usage
		FROM products p
		LEFT JOIN product_variants v ON v.product_id = p.id
		WHERE p.is_active = TRUE
		ORDER BY p.id DESC
	`
	rows, err := r.db.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	productMap := make(map[int]*product.Product)
	var productIDs []int

	for rows.Next() {
		var p product.Product
		var vID sql.NullInt64
		var vSku, vName sql.NullString
		var vPrice sql.NullFloat64
		var vStock sql.NullInt64
		var vIsActive sql.NullBool
		var vMaterialID sql.NullInt64
		var vMaterialUsage sql.NullFloat64

		err := rows.Scan(
			&p.ID, &p.Name, &p.Description, &p.BasePrice,
			&vID, &vSku, &vName, &vPrice, &vStock, &vIsActive, &vMaterialID, &vMaterialUsage,
		)
		if err != nil {
			return nil, err
		}

		if _, exists := productMap[p.ID]; !exists {
			productMap[p.ID] = &p
			productIDs = append(productIDs, p.ID)
		}

		if vID.Valid {
			variant := product.ProductVariant{
				ID:          int(vID.Int64),
				ProductID:   p.ID,
				SKU:         vSku.String,
				VariantName: vName.String,
				Price:       vPrice.Float64,
				Stock:       int(vStock.Int64),
				IsActive:    vIsActive.Bool,
			}
			if vMaterialID.Valid {
				matID := int(vMaterialID.Int64)
				variant.MaterialID = &matID
			}
			if vMaterialUsage.Valid {
				variant.MaterialUsage = vMaterialUsage.Float64
			}
			productMap[p.ID].Variants = append(productMap[p.ID].Variants, variant)
		}
	}

	// handle error iteration (WAJIB)
	if err := rows.Err(); err != nil {
		return nil, err
	}

	var products []product.Product
	for _, id := range productIDs {
		products = append(products, *productMap[id])
	}

	// return empty slice instead of nil (best practice API)
	if len(products) == 0 {
		return []product.Product{}, nil
	}

	return products, nil
}
