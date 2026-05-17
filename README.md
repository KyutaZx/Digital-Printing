# 🖨️ Digital Printing Management System (DPMS)

[![Laravel](https://img.shields.io/badge/Frontend-Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Golang](https://img.shields.io/badge/Backend-Go_1.25-00ADD8?style=for-the-badge&logo=go&logoColor=white)](https://go.dev)
[![FastAPI](https://img.shields.io/badge/AI_Service-FastAPI-009688?style=for-the-badge&logo=fastapi&logoColor=white)](https://fastapi.tiangolo.com)
[![PostgreSQL](https://img.shields.io/badge/Database-PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**Digital Printing Management System (DPMS)** adalah ekosistem manajemen percetakan digital kelas enterprise yang terintegrasi. Platform ini dirancang menggunakan arsitektur modern berbasis microservices untuk menangani pesanan volume tinggi, alur kerja produksi yang kompleks, dan pemrosesan gambar berbasis AI.

---

## 🏛️ Arsitektur Sistem

Sistem ini dibangun dengan pendekatan *separation of concerns* untuk memastikan skalabilitas dan efisiensi performa:


Platform ini terbagi menjadi tiga layanan inti:
1.  **Frontend & Web Service (Laravel)**: Berfungsi sebagai portal utama untuk Pelanggan, Staff, dan Owner dengan sistem Blade templating yang responsif.
2.  **Core API Backend (Golang)**: Mesin utama yang menangani logika bisnis berat, transaksi keuangan, manajemen inventaris, dan operasi real-time dengan performa tinggi.
3.  **AI Microservice (Python)**: Layanan khusus berbasis TensorFlow untuk deteksi kualitas desain secara otomatis sebelum masuk ke tahap cetak.

---

## ✨ Fitur Unggulan

-   **👑 Dashboard Multi-Role**: Pengalaman yang dipersonalisasi untuk Owner (Analitik Bisnis), Staff (Manajemen Produksi), dan Pelanggan (Pelacakan Pesanan).
-   **⚙️ Alur Kerja Produksi Terotomasi**: Pemantauan status real-time dari fase desain, antrean cetak, hingga finishing dan pengiriman.
-   **🤖 Deteksi Kualitas AI**: Integrasi AI untuk mendeteksi gambar yang pecah (blur) secara otomatis, memastikan kualitas hasil cetak tetap premium.
-   **📄 Invoicing Dinamis**: Pembuatan invoice PDF secara instan menggunakan engine Golang yang sangat cepat.
-   **⚡ Operasi Real-time**: Notifikasi dan pembaruan status pesanan seketika menggunakan teknologi WebSockets.
-   **🛡️ Keamanan Enterprise**: Autentikasi berbasis JWT, pembatasan laju (Rate Limiting), dan Kontrol Akses Berbasis Peran (RBAC).

---

## 🚀 Teknologi yang Digunakan

### Frontend & Web Interface
-   **Framework**: Laravel 12.x
-   **UI Engine**: Blade & Vite
-   **Styling**: Modern CSS / Tailwind CSS
-   **Authentication**: Session-based (Web) & Sanctum (API Bridge)

### Core Backend Engine
-   **Language**: Go (Golang) 1.25+
-   **Architecture**: Clean Architecture (Domain Driven Design inspired)
-   **Framework**: Gin Gonic (High Performance)
-   **Database**: PostgreSQL
-   **Library Utama**: fpdf (Invoicing), SQLX/GORM

### AI Deep Learning Service
-   **Language**: Python 3.9+
-   **Framework**: FastAPI
-   **AI Library**: TensorFlow & Keras
-   **Fungsi Utama**: Computer Vision untuk Blur Detection pada file desain.

---

## 🛠️ Panduan Instalasi

### Prasyarat
-   PHP 8.2+ & Composer
-   Go 1.25+
-   Python 3.9+
-   PostgreSQL 15+

### 1. Konfigurasi Laravel (Frontend)
```bash
# Clone repository
git clone https://github.com/KyutaZx/digital-printing.git
cd digital-printing

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Migrasi Database
php artisan migrate

# Jalankan server
php artisan serve
```

### 2. Konfigurasi Golang API (Core)
```bash
cd golang-api

# Install dependencies
go mod download

# Jalankan server (pastikan .env sudah dikonfigurasi)
go run cmd/main.go
```

### 3. Konfigurasi Python AI (Service)
```bash
cd python-ai

# Buat virtual environment
python -m venv venv
source venv/bin/activate # Windows: venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt

# Jalankan AI Service
uvicorn main:app --reload
```

---

## 📂 Struktur Proyek

```text
.
├── app/                # Logika Aplikasi Laravel
├── golang-api/         # Core Backend (Clean Architecture)
│   ├── cmd/            # Entry points aplikasi
│   ├── internal/       # Business Logic (Usecase, Repository, Domain)
│   └── configs/        # Konfigurasi sistem Go
├── python-ai/          # Layanan Deteksi AI (FastAPI)
├── resources/          # Frontend Assets (Blade, CSS, JS)
├── public/             # File statis dan akses publik
└── storage/            # Media penyimpanan file lokal
```

---

## 📝 Dokumentasi API
Seluruh endpoint API didokumentasikan dengan lengkap. Anda dapat mengimpor file berikut ke Postman:
[Digital_Printing_API.postman_collection.json](Digital_Printing_API.postman_collection.json)

---

## 📄 Lisensi
Proyek ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

---
Dikembangkan dengan ☕ oleh **[KyutaZx](https://github.com/KyutaZx)**
