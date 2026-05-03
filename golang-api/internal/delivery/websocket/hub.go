package websocket

import (
	"encoding/json"
	"log"
	"sync"

	"github.com/gorilla/websocket"
)

// Hub menyimpan referensi ke seluruh koneksi websocket klien yang sedang aktif
type Hub struct {
	// Kunci map adalah userID
	clients map[int]*websocket.Conn
	mu      sync.RWMutex
}

// GlobalHub adalah instance tunggal dari Hub
var GlobalHub = &Hub{
	clients: make(map[int]*websocket.Conn),
}

// AddClient menyimpan koneksi ws untuk user tertentu
func (h *Hub) AddClient(userID int, conn *websocket.Conn) {
	h.mu.Lock()
	defer h.mu.Unlock()
	h.clients[userID] = conn
}

// RemoveClient menghapus koneksi ws
func (h *Hub) RemoveClient(userID int) {
	h.mu.Lock()
	defer h.mu.Unlock()
	if conn, ok := h.clients[userID]; ok {
		conn.Close()
		delete(h.clients, userID)
	}
}

// BroadcastToUser mengirim pesan JSON ke user tertentu (jika sedang online)
func (h *Hub) BroadcastToUser(userID int, message interface{}) {
	h.mu.RLock()
	conn, exists := h.clients[userID]
	h.mu.RUnlock()

	if !exists {
		// User sedang offline (tidak tersambung WebSocket), abaikan saja.
		return
	}

	payload, err := json.Marshal(message)
	if err != nil {
		log.Println("Gagal marshal websocket message:", err)
		return
	}

	h.mu.Lock()
	err = conn.WriteMessage(websocket.TextMessage, payload)
	h.mu.Unlock()

	if err != nil {
		log.Printf("Gagal kirim pesan ke user %d: %v\n", userID, err)
		h.RemoveClient(userID)
	}
}
