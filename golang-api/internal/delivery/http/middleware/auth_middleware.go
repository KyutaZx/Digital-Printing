package middleware

import (
	"net/http"
	"strings"

	"github.com/gin-gonic/gin"
	"github.com/golang-jwt/jwt/v5"

	jwtpkg "golang-api/internal/infrastructure/jwt"
)

func AuthMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {

		authHeader := c.GetHeader("Authorization")
		if authHeader == "" {
			c.JSON(http.StatusUnauthorized, gin.H{"message": "missing token"})
			c.Abort()
			return
		}

		tokenString := strings.Replace(authHeader, "Bearer ", "", 1)

		// Parse pakai MapClaims — secret diambil dari satu sumber (package jwt)
		token, err := jwt.Parse(tokenString, func(t *jwt.Token) (interface{}, error) {
			return jwtpkg.GetJWTSecret(), nil
		})

		if err != nil || !token.Valid {
			c.JSON(http.StatusUnauthorized, gin.H{"message": "invalid token"})
			c.Abort()
			return
		}

		// Ekstrak claims dari MapClaims
		claims, ok := token.Claims.(jwt.MapClaims)
		if !ok {
			c.JSON(http.StatusUnauthorized, gin.H{"message": "invalid token claims"})
			c.Abort()
			return
		}

		// Ambil user_id (disimpan sebagai float64 di MapClaims JSON)
		userIDFloat, ok := claims["user_id"].(float64)
		if !ok {
			c.JSON(http.StatusUnauthorized, gin.H{"message": "invalid token: user_id not found"})
			c.Abort()
			return
		}

		role, _ := claims["role"].(string)

		// Set ke context agar bisa diakses handler
		c.Set("user_id", int(userIDFloat))
		c.Set("role", role)

		c.Next()
	}
}
