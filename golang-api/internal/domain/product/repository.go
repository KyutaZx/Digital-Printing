package product

type Repository interface {
	FindAll() ([]Product, error)
}
