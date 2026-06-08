# Jaya Mandiri — Web-Based Digital Printing OMS

Sistem Informasi Manajemen Pesanan (Order Management System/OMS) berbasis web untuk percetakan B2C **Jaya Mandiri**. Sistem ini dirancang menggunakan arsitektur *multi-service* yang mengintegrasikan layanan *front-end* yang reaktif dengan *microservices* berkinerja tinggi, dilengkapi dengan Kecerdasan Buatan (AI) untuk proses pra-cetak.

> **Repository:** [github.com/KyutaZx/Digital-Printing](https://github.com/KyutaZx/Digital-Printing)

---

## 🚀 Tech Stack Utama

Proyek ini dibangun menggunakan kombinasi teknologi modern:

- **Laravel (PHP):** Berfungsi sebagai fondasi arsitektur MVC, menangani *routing* utama, manajemen sesi, dan *Role-Based Access Control* (RBAC).
- **React.js & Tailwind CSS:** Digunakan untuk membangun antarmuka pengguna (*front-end*) yang dinamis, interaktif (SPA pada fitur tertentu), dan bergaya premium.
- **Golang (Go):** *Microservice* API berkinerja tinggi yang menangani komputasi berat, pemrosesan transaksi CRUD, manajemen keranjang, hingga *generate* dokumen Invoice PDF secara dinamis.
- **Python (FastAPI):** *Microservice* spesifik untuk menjalankan model *Artificial Intelligence* (AI) berbasis pengolahan citra (*Image Processing*) guna mendeteksi tingkat ketajaman (*blur detection*) pada file desain.
- **PostgreSQL:** Sistem basis data relasional utama untuk menjamin integritas data (ACID).
- **PM2:** Digunakan sebagai *Process Manager* pada peladen (VPS Linux) untuk menjalankan *microservice* Golang dan Python secara *background*.

---

## 👥 Hak Akses & Fitur Pengguna

Sistem ini mendukung 3 tingkat pengguna (*Roles*):

### 1. Customer (Pelanggan)
- Eksplorasi katalog produk dan manajemen keranjang belanja.
- Proses *Checkout* mandiri.
- **Validasi Desain AI:** Mengunggah file desain yang akan langsung dianalisis ketajamannya oleh algoritma AI.
- Unggah bukti pembayaran (Transfer Bank).
- Pelacakan riwayat pesanan (*Order Tracking*) secara *real-time*.
- Mengunduh *Invoice* resmi.

### 2. Staff (Admin/Operasional)
- Verifikasi bukti pembayaran pelanggan.
- **Review Desain:** Meninjau desain yang telah lolos AI, serta memberikan catatan jika desain perlu direvisi (maksimal 3 kali revisi).
- Manajemen alur produksi (memperbarui status: *Diproses* -> *Selesai*).

### 3. Owner (Pemilik Bisnis)
- Manajemen data Katalog Produk (tambah, edit, *soft delete*).
- Manajemen persediaan Material (penyesuaian stok masuk/keluar).
- Manajemen Karyawan (tambah akun *Staff*, *banned user*).
- Mengekstrak Laporan Pendapatan berdasarkan filter tanggal.
- Memantau *Audit Log* (rekam jejak seluruh tindakan di dalam sistem).

---

## 🔄 Alur Pesanan Utama

```text
Eksplorasi Katalog → Tambah ke Keranjang → Checkout 
→ Upload Desain (Divalidasi AI) → Upload Pembayaran 
→ Verifikasi Pembayaran (Staff) → Review Desain Akhir (Staff) 
→ Produksi Printing → Siap Diambil/Dikirim → Pesanan Selesai
```

---

## 📂 Struktur Repositori

```text
.
├── app/                  # Controller & Middleware Laravel
├── bootstrap/            # Konfigurasi boot Laravel
├── config/               # File konfigurasi utama
├── database/             # Migrasi & Seeder PostgreSQL
├── docs/                 # Dokumentasi PRD, Laporan Skripsi
├── golang-api/           # Source code Microservice API (Golang)
├── public/               # Entry point Laravel & asset statis
├── python-ai/            # Source code Microservice AI Blur (Python)
├── resources/
│   ├── js/               # Komponen React.js (Pages & UI)
│   ├── css/              # Konfigurasi Tailwind CSS
│   └── views/            # File Blade (kerangka utama HTML)
├── routes/               # Definisi endpoint (web.php)
└── storage/              # File upload pelanggan & log sistem
```

---

## ⚙️ Deployment & Instalasi (VPS Linux)

Aplikasi ini dioptimalkan untuk berjalan pada Virtual Private Server (VPS) berbasis Linux Ubuntu.

1. **Clone Repository**
   ```bash
   git clone https://github.com/KyutaZx/Digital-Printing.git
   cd Digital-Printing
   ```
2. **Setup Laravel & Frontend**
   ```bash
   composer install
   npm install
   npm run build
   php artisan migrate
   ```
3. **Setup Golang API**
   ```bash
   cd golang-api
   go build -o api-server cmd/server/main.go
   pm2 start api-server --name "golang-api"
   ```
4. **Setup Python AI**
   ```bash
   cd python-ai
   python3 -m venv venv
   source venv/bin/activate
   pip install -r requirements.txt
   pm2 start main.py --interpreter ./venv/bin/python --name "python-ai"
   ```
