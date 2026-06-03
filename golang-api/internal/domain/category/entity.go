package category

import "time"

// Category represents the category entity
type Category struct {
	ID          int       `json:"id"`
	Name        string    `json:"name"`
	Description *string   `json:"description"`
	Image       *string   `json:"image"`
	CreatedAt   time.Time `json:"created_at"`
}

// CategoryRepository interface defines the methods that any category repository must implement
type CategoryRepository interface {
	GetAll() ([]Category, error)
	GetByID(id int) (*Category, error)
	Create(cat *Category) error
	Update(cat *Category) error
	Delete(id int) error
	UpdateImage(id int, imagePath string) error
}

// CategoryUsecase interface defines the methods that any category usecase must implement
type CategoryUsecase interface {
	GetAll() ([]Category, error)
	GetByID(id int) (*Category, error)
	Create(cat *Category) error
	Update(cat *Category) error
	Delete(id int) error
	UpdateImage(id int, imagePath string) error
}
