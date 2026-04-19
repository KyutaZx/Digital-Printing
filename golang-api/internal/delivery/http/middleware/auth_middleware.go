package middleware

import (
	"net/http"
	"strings"

	"github.com/gin-gonic/gin"
	"github.com/golang-jwt/jwt/v5"
)

var SECRET_KEY = []byte("secret")

// ========================
// CUSTOM CLAIMS
// ========================
type CustomClaims struct {
	UserID int    `json:"user_id"`
	Role   string `json:"role"`
	jwt.RegisteredClaims
}

func AuthMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {

		authHeader := c.GetHeader("Authorization")
		if authHeader == "" {
			c.JSON(http.StatusUnauthorized, gin.H{"message": "missing token"})
			c.Abort()
			return
		}

		// ========================
		// FORMAT: Bearer TOKEN
		// ========================
		tokenString := strings.Replace(authHeader, "Bearer ", "", 1)

		// ========================
		// PARSE TOKEN WITH CLAIMS
		// ========================
		claims := &CustomClaims{}

		token, err := jwt.ParseWithClaims(tokenString, claims, func(t *jwt.Token) (interface{}, error) {
			return SECRET_KEY, nil
		})

		if err != nil || !token.Valid {
			c.JSON(http.StatusUnauthorized, gin.H{"message": "invalid token"})
			c.Abort()
			return
		}

		// ========================
		// SET KE CONTEXT
		// ========================
		c.Set("user_id", claims.UserID)
		c.Set("role", claims.Role)

		c.Next()
	}
}
