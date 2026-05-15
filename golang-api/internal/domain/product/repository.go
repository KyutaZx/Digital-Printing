package product

type Repository interface {
	FindAll() ([]Product, error)
	FindByID(id int) (*Product, error)
	Create(product *Product) error
	Update(product *Product) error
	Delete(id int) error
}
