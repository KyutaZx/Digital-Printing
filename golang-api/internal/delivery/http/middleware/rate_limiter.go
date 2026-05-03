package middleware

import (
	"net/http"
	"sync"
	"time"

	"github.com/gin-gonic/gin"
	"golang.org/x/time/rate"
)

// IPRateLimiter menyimpan state limit per IP
type IPRateLimiter struct {
	ips map[string]*rate.Limiter
	mu  *sync.RWMutex
	r   rate.Limit
	b   int
}

// NewIPRateLimiter menginisialisasi rate limiter (r = requests per second, b = burst limit)
func NewIPRateLimiter(r rate.Limit, b int) *IPRateLimiter {
	i := &IPRateLimiter{
		ips: make(map[string]*rate.Limiter),
		mu:  &sync.RWMutex{},
		r:   r,
		b:   b,
	}

	// Secara periodik bersihkan IP lama untuk menghindari memory leak (bisa ditambahkan nanti)
	go i.cleanupRoutine()

	return i
}

// GetLimiter mengembalikan limiter untuk IP tertentu
func (i *IPRateLimiter) GetLimiter(ip string) *rate.Limiter {
	i.mu.RLock()
	limiter, exists := i.ips[ip]
	i.mu.RUnlock()

	if !exists {
		i.mu.Lock()
		// Cek lagi karena mungkin IP dibuat oleh goroutine lain saat menunggu lock
		limiter, exists = i.ips[ip]
		if !exists {
			limiter = rate.NewLimiter(i.r, i.b)
			i.ips[ip] = limiter
		}
		i.mu.Unlock()
	}

	return limiter
}

// cleanupRoutine membersihkan map IP setiap jam (simulasi kasar)
func (i *IPRateLimiter) cleanupRoutine() {
	for {
		time.Sleep(1 * time.Hour)
		i.mu.Lock()
		// Re-create map (IP aktif akan masuk lagi di request berikutnya)
		i.ips = make(map[string]*rate.Limiter)
		i.mu.Unlock()
	}
}

// RateLimitMiddleware adalah middleware Gin untuk membatasi request
func RateLimitMiddleware(limiter *IPRateLimiter) gin.HandlerFunc {
	return func(c *gin.Context) {
		ip := c.ClientIP()
		l := limiter.GetLimiter(ip)

		if !l.Allow() {
			c.JSON(http.StatusTooManyRequests, gin.H{
				"message": "Terlalu banyak permintaan. Silakan coba lagi beberapa saat kemudian.",
			})
			c.Abort()
			return
		}

		c.Next()
	}
}
