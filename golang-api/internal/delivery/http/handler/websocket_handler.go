package handler

import (
	"log"
	"net/http"

	"golang-api/internal/delivery/websocket"

	"github.com/gin-gonic/gin"
	ws "github.com/gorilla/websocket"
)

var upgrader = ws.Upgrader{
	CheckOrigin: func(r *http.Request) bool {
		// Mengizinkan semua origin untuk development, sebaiknya dibatasi di production
		return true
	},
}

// WebsocketHandler menangani koneksi websocket
type WebsocketHandler struct{}

func NewWebsocketHandler() *WebsocketHandler {
	return &WebsocketHandler{}
}

// Connect menangani request GET /ws
// Membutuhkan user_id dari middleware auth
func (h *WebsocketHandler) Connect(c *gin.Context) {
	userID, exists := c.Get("user_id")
	if !exists {
		c.JSON(http.StatusUnauthorized, gin.H{"message": "Unauthorized"})
		return
	}

	uid, ok := userID.(int)
	if !ok {
		c.JSON(http.StatusBadRequest, gin.H{"message": "Invalid user ID"})
		return
	}

	// Upgrade HTTP connection to WebSocket
	conn, err := upgrader.Upgrade(c.Writer, c.Request, nil)
	if err != nil {
		log.Println("Gagal upgrade ke websocket:", err)
		return
	}

	// Daftarkan ke Hub
	websocket.GlobalHub.AddClient(uid, conn)
	log.Printf("User %d terhubung ke WebSocket\n", uid)

	// Dengarkan pesan jika klien terputus (Read loop)
	go func() {
		defer func() {
			websocket.GlobalHub.RemoveClient(uid)
			log.Printf("User %d terputus dari WebSocket\n", uid)
		}()

		for {
			_, _, err := conn.ReadMessage()
			if err != nil {
				// Client terputus (tutup tab, dsb)
				break
			}
		}
	}()
}
