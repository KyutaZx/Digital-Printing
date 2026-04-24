package main

import (
	"log"
	"os"

	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"

	"golang-api/internal/delivery/http/handler"
	"golang-api/internal/delivery/http/routes"
	"golang-api/internal/infrastructure/database"
	postgresRepo "golang-api/internal/repository/postgres"
	"golang-api/internal/usecase"
)

func main() {
	// =========================================================================
	// 1. LOAD ENVIRONMENT VARIABLES
	// =========================================================================
	if err := godotenv.Load(); err != nil {
		log.Println("⚠️  File .env tidak ditemukan, menggunakan system env")
	}

	// SET GIN MODE
	if os.Getenv("APP_ENV") == "production" {
		gin.SetMode(gin.ReleaseMode)
	}

	// =========================================================================
	// 2. DATABASE CONNECTION
	// =========================================================================
	dbConn, err := database.NewPostgresConnection()
	if err != nil {
		log.Fatal("❌ Gagal terhubung ke PostgreSQL:", err)
	}
	defer dbConn.Close()

	log.Println("✅ Database PostgreSQL terhubung")

	// =========================================================================
	// 3. REPOSITORY INITIALIZATION
	// =========================================================================
	userRepo := postgresRepo.NewUserRepository(dbConn)
	productRepo := postgresRepo.NewProductRepository(dbConn)
	orderRepo := postgresRepo.NewOrderRepository(dbConn)
	cartRepo := postgresRepo.NewCartRepository(dbConn)
	paymentRepo := postgresRepo.NewPaymentRepository(dbConn)
	auditRepo := postgresRepo.NewAuditRepository(dbConn) // Wajib ada untuk logging

	// 🔥 TAMBAHAN: Inisialisasi Production & Material Repository
	productionRepo := postgresRepo.NewProductionRepository(dbConn)
	materialRepo := postgresRepo.NewMaterialRepository(dbConn)

	// =========================================================================
	// 4. USECASE INITIALIZATION (Dependency Injection)
	// =========================================================================
	authUsecase := usecase.NewAuthUsecase(userRepo, auditRepo)
	productUsecase := usecase.NewProductUsecase(productRepo)
	orderUsecase := usecase.NewOrderUsecase(orderRepo, auditRepo)
	cartUsecase := usecase.NewCartUsecase(cartRepo)
	paymentUsecase := usecase.NewPaymentUsecase(paymentRepo, orderRepo, auditRepo)

	// 🔥 TAMBAHAN: Inisialisasi Production & Material Usecase
	productionUsecase := usecase.NewProductionUsecase(productionRepo, auditRepo)
	materialUsecase := usecase.NewMaterialUsecase(materialRepo, auditRepo)

	// =========================================================================
	// 5. HANDLER INITIALIZATION
	// =========================================================================
	authHandler := handler.NewAuthHandler(authUsecase)
	productHandler := handler.NewProductHandler(productUsecase)
	orderHandler := handler.NewOrderHandler(orderUsecase)
	cartHandler := handler.NewCartHandler(cartUsecase)
	paymentHandler := handler.NewPaymentHandler(paymentUsecase)

	// 🔥 TAMBAHAN: Inisialisasi Production & Material Handler
	productionHandler := handler.NewProductionHandler(productionUsecase)
	materialHandler := handler.NewMaterialHandler(materialUsecase)

	// =========================================================================
	// 6. ROUTER & SERVER SETUP
	// =========================================================================
	r := gin.Default()

	// Keamanan Proxy
	if err := r.SetTrustedProxies([]string{"127.0.0.1"}); err != nil {
		log.Fatal(err)
	}

	// Setup Routes Utama
	routes.SetupRoutes(
		r,
		authHandler,
		productHandler,
		orderHandler,
		cartHandler,
		paymentHandler,
		productionHandler, // 🔥 DIMASUKKAN KE PARAMETER SETUP ROUTES
		materialHandler,   // 🔥 DIMASUKKAN KE PARAMETER SETUP ROUTES
	)

	// RUN SERVER
	port := os.Getenv("APP_PORT")
	if port == "" {
		port = "8080"
	}

	log.Printf("🚀 OMS Printing Server berjalan di port: %s", port)

	if err := r.Run(":" + port); err != nil {
		log.Fatal("❌ Gagal menjalankan server:", err)
	}
}
