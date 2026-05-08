package product

type Repository interface {
	FindAll() ([]Product, error)
	Create(product *Product) error
	Update(product *Product) error
	UpdateImage(id int, imagePath string) error
	Delete(id int) error
}
