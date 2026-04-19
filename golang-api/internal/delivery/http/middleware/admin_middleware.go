package middleware

import "github.com/gin-gonic/gin"

// ========================
// OWNER ONLY MIDDLEWARE (FINAL)
// ========================
func OwnerOnly() gin.HandlerFunc {
	return func(c *gin.Context) {

		roleInterface, exists := c.Get("role")
		if !exists {
			c.JSON(401, gin.H{"message": "unauthorized"})
			c.Abort()
			return
		}

		role, ok := roleInterface.(string)
		if !ok || role != "owner" {
			c.JSON(403, gin.H{"message": "only owner can access"})
			c.Abort()
			return
		}

		c.Next()
	}
}
