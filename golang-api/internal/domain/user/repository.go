package user

import "context"

type Repository interface {
	// Tambahkan context.Context agar sinkron dengan pemanggilan di AuthUsecase
	FindByEmail(ctx context.Context, email string) (*User, error)

	// Tambahkan context.Context untuk mendukung tracking dan timeout database
	Create(ctx context.Context, user *User) error

	// Tambahkan FindByID untuk kebutuhan middleware atau profil user nantinya
	FindByID(ctx context.Context, id int) (*User, error)
}
