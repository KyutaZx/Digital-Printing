package postgres

import (
	"database/sql"
	"errors"
	"time"

	"golang-api/internal/domain/category"
)

type categoryRepository struct {
	db *sql.DB
}

// NewCategoryRepository creates a new category repository
func NewCategoryRepository(db *sql.DB) category.CategoryRepository {
	return &categoryRepository{db: db}
}

func (r *categoryRepository) GetAll() ([]category.Category, error) {
	query := `SELECT id, name, description, image, created_at FROM categories ORDER BY id ASC`
	rows, err := r.db.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var categories []category.Category
	for rows.Next() {
		var cat category.Category
		err := rows.Scan(&cat.ID, &cat.Name, &cat.Description, &cat.Image, &cat.CreatedAt)
		if err != nil {
			return nil, err
		}
		categories = append(categories, cat)
	}
	return categories, nil
}

func (r *categoryRepository) GetByID(id int) (*category.Category, error) {
	query := `SELECT id, name, description, image, created_at FROM categories WHERE id = $1`
	row := r.db.QueryRow(query, id)

	var cat category.Category
	err := row.Scan(&cat.ID, &cat.Name, &cat.Description, &cat.Image, &cat.CreatedAt)
	if err != nil {
		if errors.Is(err, sql.ErrNoRows) {
			return nil, errors.New("category not found")
		}
		return nil, err
	}
	return &cat, nil
}

func (r *categoryRepository) Create(cat *category.Category) error {
	query := `INSERT INTO categories (name, description, image, created_at) VALUES ($1, $2, $3, $4) RETURNING id`
	cat.CreatedAt = time.Now()
	err := r.db.QueryRow(query, cat.Name, cat.Description, cat.Image, cat.CreatedAt).Scan(&cat.ID)
	return err
}

func (r *categoryRepository) Update(cat *category.Category) error {
	query := `UPDATE categories SET name = $1, description = $2 WHERE id = $3`
	_, err := r.db.Exec(query, cat.Name, cat.Description, cat.ID)
	return err
}

func (r *categoryRepository) Delete(id int) error {
	query := `DELETE FROM categories WHERE id = $1`
	_, err := r.db.Exec(query, id)
	return err
}

func (r *categoryRepository) UpdateImage(id int, imagePath string) error {
	query := `UPDATE categories SET image = $1 WHERE id = $2`
	_, err := r.db.Exec(query, imagePath, id)
	return err
}
