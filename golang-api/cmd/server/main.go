package main

import (
	"context"
	"log"
	"os"

	"github.com/robfig/cron/v3"

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

	// 🔥 TAMBAHAN: Inisialisasi Production, Material & Design Repository
	productionRepo := postgresRepo.NewProductionRepository(dbConn)
	materialRepo := postgresRepo.NewMaterialRepository(dbConn)
	designRepo := postgresRepo.NewDesignRepository(dbConn)
	reportRepo := postgresRepo.NewReportRepository(dbConn)

	// =========================================================================
	// 4. USECASE INITIALIZATION (Dependency Injection)
	// =========================================================================
	authUsecase := usecase.NewAuthUsecase(userRepo, auditRepo)
	productUsecase := usecase.NewProductUsecase(productRepo)
	orderUsecase := usecase.NewOrderUsecase(orderRepo, auditRepo)
	cartUsecase := usecase.NewCartUsecase(cartRepo)
	paymentUsecase := usecase.NewPaymentUsecase(paymentRepo, orderRepo, auditRepo)

	// 🔥 TAMBAHAN: Inisialisasi Production, Material & Design Usecase
	productionUsecase := usecase.NewProductionUsecase(productionRepo, auditRepo)
	materialUsecase := usecase.NewMaterialUsecase(materialRepo, auditRepo)
	designUsecase := usecase.NewDesignUsecase(designRepo, auditRepo)
	reportUsecase := usecase.NewReportUsecase(reportRepo)
	userUsecase := usecase.NewUserUsecase(userRepo, auditRepo)

	// =========================================================================
	// 5. HANDLER INITIALIZATION
	// =========================================================================
	authHandler := handler.NewAuthHandler(authUsecase)
	productHandler := handler.NewProductHandler(productUsecase)
	orderHandler := handler.NewOrderHandler(orderUsecase)
	cartHandler := handler.NewCartHandler(cartUsecase)
	paymentHandler := handler.NewPaymentHandler(paymentUsecase)

	// 🔥 TAMBAHAN: Inisialisasi Production, Material & Design Handler
	productionHandler := handler.NewProductionHandler(productionUsecase)
	materialHandler := handler.NewMaterialHandler(materialUsecase)
	designHandler := handler.NewDesignHandler(designUsecase)
	reportHandler := handler.NewReportHandler(reportUsecase)
	userHandler := handler.NewUserHandler(userUsecase)

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
		designHandler,     // 🔥 DIMASUKKAN KE PARAMETER SETUP ROUTES
		reportHandler,     // 🔥 DIMASUKKAN KE PARAMETER SETUP ROUTES
		userHandler,       // 🔥 TAMBAHAN UNTUK USER MANAGEMENT
		userRepo,          // 🔥 TAMBAHAN UNTUK MIDDLEWARE
	)

	// =========================================================================
	// DEBUG ENDPOINT (HAPUS SETELAH FIX)
	// =========================================================================
	r.GET("/debug/designs", func(c *gin.Context) {
		type DebugDesign struct {
			ID          int    `json:"id"`
			OrderItemID int    `json:"order_item_id"`
			FilePath    string `json:"file_path"`
			Version     int    `json:"version"`
			UploadedBy  int    `json:"uploaded_by"`
			CreatedAt   string `json:"created_at"`
		}
		type DebugOrderItem struct {
			ID      int    `json:"id"`
			OrderID int    `json:"order_id"`
			Status  string `json:"order_status"`
		}
		var designs []DebugDesign
		rows, err := dbConn.QueryContext(c.Request.Context(), `
			SELECT df.id, df.order_item_id, df.file_path, df.version, df.uploaded_by, df.created_at::text
			FROM design_files df
			ORDER BY df.created_at DESC
			LIMIT 50
		`)
		if err != nil {
			c.JSON(500, map[string]string{"error": err.Error()})
			return
		}
		defer rows.Close()
		for rows.Next() {
			var d DebugDesign
			rows.Scan(&d.ID, &d.OrderItemID, &d.FilePath, &d.Version, &d.UploadedBy, &d.CreatedAt)
			designs = append(designs, d)
		}

		var orderItems []DebugOrderItem
		rows2, err := dbConn.QueryContext(c.Request.Context(), `
			SELECT oi.id, oi.order_id, o.status
			FROM order_items oi
			JOIN orders o ON o.id = oi.order_id
			ORDER BY oi.id DESC
			LIMIT 20
		`)
		if err == nil {
			defer rows2.Close()
			for rows2.Next() {
				var oi DebugOrderItem
				rows2.Scan(&oi.ID, &oi.OrderID, &oi.Status)
				orderItems = append(orderItems, oi)
			}
		}

		c.JSON(200, map[string]interface{}{
			"design_files_count": len(designs),
			"design_files":       designs,
			"order_items":        orderItems,
		})
	})


	// =========================================================================
	// 6. SETUP CRON JOBS (BACKGROUND TASKS)
	// =========================================================================
	c := cron.New()
	_, err = c.AddFunc("@hourly", func() {
		log.Println("⏰ Menjalankan Auto-Cancel Unpaid Orders...")
		ctx := context.Background()
		if err := orderUsecase.AutoCancelUnpaidOrders(ctx); err != nil {
			log.Println("❌ Gagal menjalankan Auto-Cancel:", err)
		} else {
			log.Println("✅ Auto-Cancel Unpaid Orders selesai.")
		}
	})
	if err != nil {
		log.Println("⚠️ Gagal mendaftarkan Cron Job:", err)
	}
	c.Start()

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
