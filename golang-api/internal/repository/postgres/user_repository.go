package postgres

import (
	"context"
	"database/sql"
	"errors"
	"log"

	"golang-api/internal/domain/user"
)

type userRepository struct {
	db *sql.DB
}

func NewUserRepository(db *sql.DB) user.Repository {
	return &userRepository{db: db}
}

// =========================================================================
// FIND USER BY EMAIL
// =========================================================================
func (r *userRepository) FindByEmail(ctx context.Context, email string) (*user.User, error) {
	var u user.User

	query := `
		SELECT id, role_id, name, email, password
		FROM public.users
		WHERE email = $1
		LIMIT 1
	`

	// Gunakan ctx yang dikirim dari Usecase agar tracing & timeout sinkron
	err := r.db.QueryRowContext(ctx, query, email).Scan(
		&u.ID,
		&u.RoleID,
		&u.Name,
		&u.Email,
		&u.Password,
	)

	if errors.Is(err, sql.ErrNoRows) {
		return nil, nil
	}

	if err != nil {
		log.Println("🔥 QUERY ERROR (FindByEmail):", err)
		return nil, err
	}

	return &u, nil
}

// =========================================================================
// CREATE USER (REGISTER)
// =========================================================================
func (r *userRepository) Create(ctx context.Context, u *user.User) error {
	query := `
		INSERT INTO public.users (name, email, password, role_id)
		VALUES ($1, $2, $3, $4)
		RETURNING id
	`

	// Eksekusi dengan context kiriman usecase
	err := r.db.QueryRowContext(ctx, query,
		u.Name,
		u.Email,
		u.Password,
		u.RoleID,
	).Scan(&u.ID)

	if err != nil {
		log.Println("🔥 QUERY ERROR (CreateUser):", err)
		return err
	}

	return nil
}

// =========================================================================
// FIND USER BY ID (Penting untuk Auth Middleware)
// =========================================================================
func (r *userRepository) FindByID(ctx context.Context, id int) (*user.User, error) {
	var u user.User
	query := `SELECT id, name, email, role_id FROM public.users WHERE id = $1`

	err := r.db.QueryRowContext(ctx, query, id).Scan(&u.ID, &u.Name, &u.Email, &u.RoleID)
	if errors.Is(err, sql.ErrNoRows) {
		return nil, nil
	}
	return &u, err
}
