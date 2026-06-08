# Panduan Deployment Server (Ubuntu 22.04)

Karena Anda menggunakan VPS kosong dengan spesifikasi 1 Core dan 1 GB RAM, proses instalasi ini harus dilakukan berurutan dengan sangat hati-hati. Cukup *copy-paste* perintah di kotak hitam ke dalam terminal Anda dan tekan **Enter**.

---

## Tahap 1: Masuk ke Server (SSH)
Buka **PowerShell** atau **Command Prompt** di laptop Anda, lalu ketik:
```bash
ssh root@<IP_SERVER_ANDA>
```
*(Saat diminta password, masukkan `<PASSWORD_VPS_ANDA>`. Password memang tidak akan terlihat saat diketik, tekan saja Enter).*

---

## Tahap 2: Membuat Swap File 2GB (SANGAT WAJIB)
Karena RAM hanya 1GB, kita wajib membuat "RAM Virtual" dari hardisk agar server tidak mati (*crash*) saat instalasi. Jalankan perintah ini satu per satu:
```bash
fallocate -l 2G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap sw 0 0' | tee -a /etc/fstab
```

---

## Tahap 3: Update Server & Install Nginx + Database
Perintah ini akan memperbarui sistem operasi dan menginstal web server Nginx serta PostgreSQL.
```bash
apt update && apt upgrade -y
apt install nginx git curl unzip postgresql postgresql-contrib -y
```

---

## Tahap 4: Install Bahasa Pemrograman
Kita akan menginstal **PHP 8.2 & Composer** (untuk Laravel), **Node.js** (untuk React Frontend), dan **Golang** (untuk API).

**1. Install PHP & Composer**
```bash
apt install software-properties-common -y
add-apt-repository ppa:ondrej/php -y
apt update
apt install php8.2-fpm php8.2-cli php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl unzip curl -y
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

**2. Install Node.js (versi 20)**
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs
```

**3. Install Golang**
```bash
wget https://go.dev/dl/go1.22.1.linux-amd64.tar.gz
rm -rf /usr/local/go && tar -C /usr/local -xzf go1.22.1.linux-amd64.tar.gz
echo 'export PATH=$PATH:/usr/local/go/bin' >> ~/.profile
source ~/.profile
rm go1.22.1.linux-amd64.tar.gz
```

---

## Tahap 5: Setup Database PostgreSQL
Kita akan membuat user dan database bernama `digital_printing`.
```bash
sudo -u postgres psql
```
Setelah tulisan berubah menjadi `postgres=#`, ketik perintah SQL ini satu per satu:
```sql
CREATE DATABASE digital_printing;
CREATE USER admin_printing WITH ENCRYPTED PASSWORD '<PASSWORD_DATABASE_ANDA>';
GRANT ALL PRIVILEGES ON DATABASE digital_printing TO admin_printing;
ALTER DATABASE digital_printing OWNER TO admin_printing;
\q
```
*(Perintah `\q` digunakan untuk keluar dari database).*

---

## Tahap 6: Download Source Code dari GitHub
Kita akan menaruh file website di folder `/var/www/`.
```bash
cd /var/www/
git clone https://github.com/KyutaZx/Digital-Printing.git
cd Digital-Printing
```

---

## Tahap 7: Deploy Golang API (Backend)
Sekarang posisi Anda ada di folder `/var/www/Digital-Printing`. Kita akan *build* API-nya.

**1. Compile Golang**
```bash
cd golang-api
go mod tidy
go build -o api-server cmd/server/main.go
```

**2. Buat File .env Golang**
```bash
nano .env
```
Layar akan masuk ke mode edit teks. *Copy-paste* isi di bawah ini ke dalamnya:
```ini
PORT=8080
DB_HOST=127.0.0.1
DB_PORT=5432
DB_USER=admin_printing
DB_PASSWORD=<PASSWORD_DATABASE_ANDA>
DB_NAME=digital_printing
JWT_SECRET=<SUPER_SECRET_KEY_ANDA>
FRONTEND_URL=http://<IP_SERVER_ATAU_DOMAIN_ANDA>
```
*(Tekan `Ctrl + X`, lalu ketik `Y`, lalu tekan `Enter` untuk menyimpan).*

**3. Menjalankan API di Background 24 Jam**
Agar API jalan terus menerus, kita akan gunakan `pm2` buatan Node.js (karena sangat gampang).
```bash
npm install -g pm2
pm2 start ./api-server --name "golang-api"
pm2 save
pm2 startup
```

---

## Tahap 8: Deploy Laravel & React (Frontend)
Sekarang kita kembali ke folder utama proyek untuk mengatur Laravel.

**1. Setup Laravel & Install Dependency**
```bash
cd /var/www/Digital-Printing
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

**2. Buat File .env Laravel**
```bash
cp .env.example .env
nano .env
```
Ubah bagian database menjadi seperti ini, dan pastikan mengatur `GOLANG_API_URL`:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=digital_printing
DB_USERNAME=admin_printing
DB_PASSWORD=<PASSWORD_DATABASE_ANDA>

GOLANG_API_URL=http://127.0.0.1:8080/api
GOLANG_API_BASE=http://127.0.0.1:8080
```
*(Tekan `Ctrl + X`, lalu ketik `Y`, lalu tekan `Enter` untuk menyimpan).*

**3. Generate Key & Izin Folder**
```bash
php artisan key:generate
php artisan migrate --force
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data /var/www/Digital-Printing
```

---

## Tahap 9: Mengatur Nginx (Agar Website Bisa Diakses)
Langkah terakhir! Kita atur agar IP server Anda menampilkan halaman Laravel.

**1. Hapus setting default**
```bash
rm /etc/nginx/sites-enabled/default
nano /etc/nginx/sites-available/digitalprinting
```

**2. Paste Konfigurasi Nginx ini:**
```nginx
server {
    listen 80;
    server_name <IP_SERVER_ATAU_DOMAIN_ANDA>; # Ganti dengan domain Anda jika sudah beli
    root /var/www/Digital-Printing/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Rute ke Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Rute Reverse Proxy ke Golang API
    location /api/ {
        proxy_pass http://127.0.0.1:8080/;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
*(Tekan `Ctrl + X`, ketik `Y`, lalu `Enter` untuk menyimpan).*

**3. Aktifkan dan Restart Nginx**
```bash
ln -s /etc/nginx/sites-available/digitalprinting /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

---
**SELESAI! 🎉** 
Silakan buka browser dan akses `http://<IP_SERVER_ATAU_DOMAIN_ANDA>`. Website Anda seharusnya sudah *online*!
