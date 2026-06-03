package usecase

import (
	"golang-api/internal/domain/category"
)

type categoryUsecase struct {
	repo category.CategoryRepository
}

// NewCategoryUsecase creates a new category usecase
func NewCategoryUsecase(repo category.CategoryRepository) category.CategoryUsecase {
	return &categoryUsecase{repo: repo}
}

func (u *categoryUsecase) GetAll() ([]category.Category, error) {
	return u.repo.GetAll()
}

func (u *categoryUsecase) GetByID(id int) (*category.Category, error) {
	return u.repo.GetByID(id)
}

func (u *categoryUsecase) Create(cat *category.Category) error {
	return u.repo.Create(cat)
}

func (u *categoryUsecase) Update(cat *category.Category) error {
	// check if exists
	_, err := u.repo.GetByID(cat.ID)
	if err != nil {
		return err
	}
	return u.repo.Update(cat)
}

func (u *categoryUsecase) Delete(id int) error {
	return u.repo.Delete(id)
}

func (u *categoryUsecase) UpdateImage(id int, imagePath string) error {
	return u.repo.UpdateImage(id, imagePath)
}
