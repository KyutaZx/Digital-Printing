# 🖨️ Digital Printing Management System

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Golang](https://img.shields.io/badge/Go-00ADD8?style=for-the-badge&logo=go&logoColor=white)](https://go.dev)
[![Python](https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white)](https://www.python.org)
[![FastAPI](https://img.shields.io/badge/FastAPI-009688?style=for-the-badge&logo=fastapi&logoColor=white)](https://fastapi.tiangolo.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org)

An integrated, enterprise-grade digital printing management ecosystem. This project leverages a modern microservices-inspired architecture to handle high-traffic printing orders, production workflows, and AI-driven image processing.

---

## 🏗️ System Architecture

The platform is divided into three core services to ensure scalability and separation of concerns:

1.  **Frontend (Web Interface)**: Built with **Laravel**, serving as the user-facing portal for Customers, Staff, and Owners.
2.  **Core API (Backend)**: High-performance **Golang (Gin)** service handling business logic, transactions, and real-time operations.
3.  **AI Service**: **Python (FastAPI)** service utilizing **TensorFlow** for specialized image processing and automated design analysis.

---

## ✨ Key Features

-   **👑 Multi-Role Dashboard**: Tailored experiences for Owners (Analytics), Staff (Production Management), and Customers (Order Tracking).
-   **⚙️ Production Workflow**: Real-time status updates from design phase to final printing and finishing.
-   **📄 Automated Invoicing**: Dynamic PDF generation for all transactions using Golang's high-speed libraries.
-   **🤖 AI Design Assistance**: Automated checking and optimization of design files to ensure printing quality.
-   **💳 Payment Integration**: Secure transaction handling with detailed logging.
-   **⚡ Real-time Updates**: Interactive features powered by WebSockets.
-   **🛡️ Security First**: JWT-based authentication, Rate Limiting, and Role-Based Access Control (RBAC).

---

## 🚀 Tech Stack

### Frontend & Web Service
-   **Framework**: Laravel 10.x
-   **UI Engine**: Blade & Vite
-   **Styling**: Vanilla CSS / Tailwind CSS
-   **Authentication**: Session-based (Web) & Sanctum/JWT (API Interaction)

### Core API Backend
-   **Language**: Go (Golang) 1.25+
-   **Web Framework**: Gin Gonic
-   **Database**: PostgreSQL
-   **PDF Engine**: fpdf
-   **Caching**: Redis (Planned/Integrated)

### AI Microservice
-   **Language**: Python 3.x
-   **Framework**: FastAPI
-   **Machine Learning**: TensorFlow
-   **Image Processing**: Pillow (PIL)

---

## 🛠️ Getting Started

### Prerequisites
-   PHP 8.2+ & Composer
-   Go 1.21+
-   Python 3.9+
-   PostgreSQL 15+

### 1. Setting up Laravel (Frontend)
```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

### 2. Setting up Golang API
```bash
cd golang-api

# Install dependencies
go mod download

# Configure .env (Database credentials)
# Start the server
go run cmd/main.go
```

### 3. Setting up Python AI Service
```bash
cd python-ai

# Create virtual environment
python -m venv venv
source venv/bin/activate # or venv\Scripts\activate on Windows

# Install dependencies
pip install -r requirements.txt

# Start FastAPI
uvicorn main:app --reload
```

---

## 📂 Project Structure

```text
.
├── app/                # Laravel Logic
├── golang-api/         # Core Backend (Go)
│   ├── cmd/            # Entry points
│   ├── internal/       # Business Logic (Domain, Usecase, Repo)
│   └── configs/        # Go Configurations
├── python-ai/          # AI Service (FastAPI)
├── resources/          # Frontend Assets (Blade, CSS, JS)
├── routes/             # Web & API Routing
└── public/             # Static Files
```

---

## 📝 API Documentation
You can find the comprehensive API documentation in the [Postman Collection](Digital_Printing_API.postman_collection.json) included in the root directory.

---

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---
Developed with ❤️ by **KyutaZx**
