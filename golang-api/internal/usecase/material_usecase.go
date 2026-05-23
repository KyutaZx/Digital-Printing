package usecase

import (
	"context"
	"errors"
	"fmt"
	"strings"

	"golang-api/internal/domain/audit"
	"golang-api/internal/domain/material"
)

type MaterialUsecase struct {
	repo      material.Repository
	auditRepo audit.Repository
}

func NewMaterialUsecase(repo material.Repository, auditRepo audit.Repository) *MaterialUsecase {
	return &MaterialUsecase{
		repo:      repo,
		auditRepo: auditRepo,
	}
}

// CreateMaterial menambahkan data material baru ke database
func (u *MaterialUsecase) CreateMaterial(ctx context.Context, req material.MaterialRequest, adminID int, ip, ua string) error {
	m := &material.Material{
		Name:  req.Name,
		Stock: req.Stock,
		Unit:  req.Unit,
	}

	err := u.repo.Create(ctx, m)
	if err != nil {
		return err
	}

	// Catat ke audit log
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:     adminID,
		Role:       "admin/owner", // asumsi role
		Action:     "create_material",
		EntityType: "materials",
		EntityID:   m.ID,
		IPAddress:  ip,
		UserAgent:  ua,
	})

	return nil
}

// GetAllMaterials mendapatkan daftar semua material
func (u *MaterialUsecase) GetAllMaterials(ctx context.Context) ([]material.Material, error) {
	return u.repo.FindAll(ctx)
}

// AdjustStock menyesuaikan stok secara manual (misal saat stok masuk dari supplier atau hilang)
func (u *MaterialUsecase) AdjustStock(ctx context.Context, materialID int, req material.MaterialStockAdjustmentRequest, adminID int, ip, ua string) error {
	// Validasi material ada
	existing, err := u.repo.FindByID(ctx, materialID)
	if err != nil {
		return err
	}
	if existing == nil {
		return errors.New("material tidak ditemukan")
	}

	// Validasi stok jika 'out' agar tidak minus berlebih
	if req.ChangeType == "out" && existing.Stock < req.Quantity {
		return fmt.Errorf("stok tidak cukup, stok saat ini: %.2f", existing.Stock)
	}

	err = u.repo.AdjustStock(ctx, materialID, req.ChangeType, req.Quantity, req.Reference)
	if err != nil {
		return err
	}

	// Catat ke audit log
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:     adminID,
		Role:       "admin/owner",
		Action:     "adjust_material_stock",
		EntityType: "materials",
		EntityID:   materialID,
		IPAddress:  ip,
		UserAgent:  ua,
	})

	return nil
}

// UpdateMaterial memperbarui nama dan unit dari material
func (u *MaterialUsecase) UpdateMaterial(ctx context.Context, id int, req material.MaterialRequest, adminID int, ip, ua string) error {
	existing, err := u.repo.FindByID(ctx, id)
	if err != nil {
		return err
	}
	if existing == nil {
		return errors.New("material tidak ditemukan")
	}

	existing.Name = req.Name
	existing.Unit = req.Unit

	err = u.repo.Update(ctx, existing)
	if err != nil {
		return err
	}

	// Catat ke audit log
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:     adminID,
		Role:       "admin/owner",
		Action:     "update_material",
		EntityType: "materials",
		EntityID:   id,
		IPAddress:  ip,
		UserAgent:  ua,
	})

	return nil
}

// DeleteMaterial menghapus material jika tidak ada constraint database yang dilanggar
func (u *MaterialUsecase) DeleteMaterial(ctx context.Context, id int, adminID int, ip, ua string) error {
	existing, err := u.repo.FindByID(ctx, id)
	if err != nil {
		return err
	}
	if existing == nil {
		return errors.New("material tidak ditemukan")
	}

	err = u.repo.Delete(ctx, id)
	if err != nil {
		if strings.Contains(err.Error(), "violates foreign key constraint") {
			return errors.New("material tidak dapat dihapus karena sedang digunakan oleh varian produk atau memiliki riwayat log stok")
		}
		return err
	}

	// Catat ke audit log
	_ = u.auditRepo.Create(ctx, &audit.AuditLog{
		UserID:     adminID,
		Role:       "admin/owner",
		Action:     "delete_material",
		EntityType: "materials",
		EntityID:   id,
		IPAddress:  ip,
		UserAgent:  ua,
	})

	return nil
}
