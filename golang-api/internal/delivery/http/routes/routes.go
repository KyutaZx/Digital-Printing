package routes

import (
	"golang-api/internal/delivery/http/handler"
	"golang-api/internal/delivery/http/middleware"

	"github.com/gin-gonic/gin"
)

func SetupRoutes(
	r *gin.Engine,
	authHandler *handler.AuthHandler,
	productHandler *handler.ProductHandler,
	orderHandler *handler.OrderHandler,
	cartHandler *handler.CartHandler,
	paymentHandler *handler.PaymentHandler,
	productionHandler *handler.ProductionHandler, // 🔥 TAMBAHAN UNTUK PRODUKSI
) {

	// ========================
	// HEALTH CHECK (OPTIONAL)
	// ========================
	r.GET("/health", func(c *gin.Context) {
		c.JSON(200, gin.H{"status": "ok"})
	})

	// ========================
	// AUTH (PUBLIC)
	// ========================
	r.POST("/login", authHandler.Login)
	r.POST("/register", authHandler.Register)

	// ========================
	// PUBLIC ROUTES
	// ========================
	r.GET("/products", productHandler.GetAll)

	// ========================
	// PROTECTED ROUTES (JWT REQUIRED)
	// ========================
	api := r.Group("/api")
	api.Use(middleware.AuthMiddleware())
	{

		// ========================
		// USER PROFILE
		// ========================
		api.GET("/profile", func(c *gin.Context) {
			userID, _ := c.Get("user_id")
			role, _ := c.Get("role")

			c.JSON(200, gin.H{
				"message": "success",
				"user_id": userID,
				"role":    role,
			})
		})

		// ========================
		// CART
		// ========================
		api.POST("/cart", cartHandler.Add)
		api.GET("/cart", cartHandler.Get)
		api.PUT("/cart", cartHandler.Update)
		api.DELETE("/cart", cartHandler.Delete)

		// ========================
		// ORDER
		// ========================
		api.POST("/orders", orderHandler.Create)
		api.POST("/checkout", orderHandler.Checkout)
		api.PUT("/orders/:id/cancel", orderHandler.Cancel)

		// ========================
		// PAYMENT (CUSTOMER)
		// ========================
		api.POST("/payments", paymentHandler.Upload)

		// ========================
		// OWNER / ADMIN ROUTES
		// ========================
		admin := api.Group("/admin")
		admin.Use(middleware.OwnerOnly()) // 🔥 RBAC OWNER
		{
			// 🔥 Pendaftaran Staf Khusus Owner
			admin.POST("/staff", authHandler.RegisterStaff)

			admin.PUT("/payments/:id/approve", paymentHandler.Approve)
			admin.PUT("/payments/:id/reject", paymentHandler.Reject)
			// admin.GET("/reports", reportHandler.GetReports)

			// 🔥 Product Management (Admin/Owner)
			admin.POST("/products", productHandler.Create)
			admin.PUT("/products/:id", productHandler.Update)
			admin.DELETE("/products/:id", productHandler.Delete)
		}

		// ========================
		// STAFF ROUTES (PRODUCTION)
		// ========================
		staff := api.Group("/staff")
		// Catatan: Validasi role staff/admin sudah kita lakukan di dalam handler,
		// jadi middleware.StaffOnly() opsional jika belum dibuat.
		{
			staff.PUT("/production/:id/start", productionHandler.Start)
			staff.PUT("/production/:id/finish", productionHandler.Finish)
		}
	}
}
