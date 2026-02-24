# Panduan Instalasi GlowUp Clinic

## Daftar Isi
1. [Persyaratan Sistem](#1-persyaratan-sistem)
2. [Instalasi di Windows](#2-instalasi-di-windows)
3. [Instalasi di macOS](#3-instalasi-di-macos)
4. [Instalasi di Linux (Ubuntu)](#4-instalasi-di-linux-ubuntu)
5. [Konfigurasi Aplikasi](#5-konfigurasi-aplikasi)
6. [Menjalankan Aplikasi](#6-menjalankan-aplikasi)
7. [Troubleshooting](#7-troubleshooting)

---

## 1. Persyaratan Sistem

### 1.1 Minimum Requirements

| Komponen | Minimum | Rekomendasi |
|----------|---------|-------------|
| RAM | 2 GB | 4 GB+ |
| Storage | 1 GB | 5 GB+ |
| CPU | Dual Core | Quad Core |

### 1.2 Software Requirements

| Software | Versi |
|----------|-------|
| PHP | 8.2 atau 8.3 |
| Composer | 2.x |
| Node.js | 18.x atau 20.x |
| NPM | 9.x atau 10.x |
| MySQL | 8.0+ atau MariaDB 10.6+ |
| Git | 2.x |

### 1.3 PHP Extensions

Pastikan extension berikut aktif:
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO MySQL
- Tokenizer
- XML
- GD atau Imagick

---

## 2. Instalasi di Windows

### 2.1 Menggunakan Laragon (Rekomendasi)

Laragon adalah development environment yang ringan dan mudah digunakan untuk Windows.

#### Langkah 1: Download dan Install Laragon

1. Download Laragon dari [laragon.org](https://laragon.org/download/)
2. Pilih versi **Laragon Full** (sudah termasuk PHP 8, MySQL, Node.js)
3. Install dengan mengikuti wizard
4. Jalankan Laragon

#### Langkah 2: Update PHP ke 8.3

1. Download PHP 8.3 dari [windows.php.net](https://windows.php.net/download/)
2. Extract ke folder `C:\laragon\bin\php\php-8.3.x`
3. Di Laragon, klik kanan → PHP → Version → Pilih php-8.3.x

#### Langkah 3: Clone Repository

```cmd
:: Buka terminal Laragon (klik kanan → Terminal)
cd C:\laragon\www
git clone https://github.com/username/clinic-glowup-web.git
cd clinic-glowup-web
```

#### Langkah 4: Install Dependencies

```cmd
:: Install PHP dependencies
composer install

:: Install Node.js dependencies
npm install
```

#### Langkah 5: Setup Environment

```cmd
:: Copy file environment
copy .env.example .env

:: Generate application key
php artisan key:generate
```

#### Langkah 6: Setup Database

1. Buka Laragon → Database → phpMyAdmin
2. Buat database baru: `glowup_clinic`
3. Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glowup_clinic
DB_USERNAME=root
DB_PASSWORD=
```

#### Langkah 7: Migrasi Database

```cmd
php artisan migrate --seed
```

#### Langkah 8: Build Assets

```cmd
npm run build
```

#### Langkah 9: Akses Aplikasi

Buka browser: `http://clinic-glowup-web.test`

---

### 2.2 Menggunakan XAMPP

#### Langkah 1: Install XAMPP

1. Download XAMPP dari [apachefriends.org](https://www.apachefriends.org/)
2. Install XAMPP
3. Jalankan Apache dan MySQL dari XAMPP Control Panel

#### Langkah 2: Install Composer

1. Download dari [getcomposer.org](https://getcomposer.org/download/)
2. Jalankan installer
3. Verifikasi: `composer --version`

#### Langkah 3: Install Node.js

1. Download dari [nodejs.org](https://nodejs.org/)
2. Install dengan default settings
3. Verifikasi:
```cmd
node --version
npm --version
```

#### Langkah 4: Clone dan Setup

```cmd
cd C:\xampp\htdocs
git clone https://github.com/username/clinic-glowup-web.git
cd clinic-glowup-web

composer install
npm install

copy .env.example .env
php artisan key:generate
```

#### Langkah 5: Konfigurasi Virtual Host

Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/clinic-glowup-web/public"
    ServerName glowup.local
    <Directory "C:/xampp/htdocs/clinic-glowup-web/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Edit `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1    glowup.local
```

#### Langkah 6: Setup Database & Migrasi

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Buat database `glowup_clinic`
3. Edit `.env` dengan kredensial database
4. Jalankan: `php artisan migrate --seed`

---

## 3. Instalasi di macOS

### 3.1 Menggunakan Laravel Herd (Rekomendasi)

Laravel Herd adalah cara tercepat untuk setup Laravel di macOS.

#### Langkah 1: Install Laravel Herd

1. Download dari [herd.laravel.com](https://herd.laravel.com/)
2. Install dengan drag ke Applications
3. Jalankan Herd

Herd sudah termasuk PHP 8.3, Nginx, dan DNS.

#### Langkah 2: Install Homebrew (jika belum ada)

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

#### Langkah 3: Install MySQL

```bash
brew install mysql
brew services start mysql

# Set root password (opsional)
mysql_secure_installation
```

#### Langkah 4: Install Node.js

```bash
brew install node
```

#### Langkah 5: Clone Repository

```bash
cd ~/Herd
git clone https://github.com/username/clinic-glowup-web.git
cd clinic-glowup-web
```

#### Langkah 6: Install Dependencies

```bash
composer install
npm install
```

#### Langkah 7: Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

#### Langkah 8: Setup Database

```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE glowup_clinic;"
```

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glowup_clinic
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Langkah 9: Migrasi dan Build

```bash
php artisan migrate --seed
npm run build
```

#### Langkah 10: Akses Aplikasi

Buka browser: `http://clinic-glowup-web.test`

---

### 3.2 Menggunakan Valet

#### Langkah 1: Install Prerequisites

```bash
# Install Homebrew (jika belum)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP 8.3
brew install php@8.3
brew link php@8.3

# Install Composer
brew install composer

# Install MySQL
brew install mysql
brew services start mysql

# Install Node.js
brew install node
```

#### Langkah 2: Install Valet

```bash
composer global require laravel/valet
valet install
```

#### Langkah 3: Park Directory

```bash
mkdir -p ~/Sites
cd ~/Sites
valet park
```

#### Langkah 4: Clone dan Setup

```bash
cd ~/Sites
git clone https://github.com/username/clinic-glowup-web.git
cd clinic-glowup-web

composer install
npm install

cp .env.example .env
php artisan key:generate
```

#### Langkah 5: Setup Database dan Migrasi

```bash
mysql -u root -e "CREATE DATABASE glowup_clinic;"
# Edit .env dengan kredensial database
php artisan migrate --seed
npm run build
```

#### Langkah 6: Akses Aplikasi

Buka browser: `http://clinic-glowup-web.test`

---

## 4. Instalasi di Linux (Ubuntu)

### 4.1 Ubuntu 22.04 / 24.04

#### Langkah 1: Update System

```bash
sudo apt update && sudo apt upgrade -y
```

#### Langkah 2: Install PHP 8.3

```bash
# Tambah repository PHP
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP dan extensions
sudo apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common \
    php8.3-mysql php8.3-xml php8.3-curl php8.3-gd php8.3-mbstring \
    php8.3-zip php8.3-bcmath php8.3-intl php8.3-readline php8.3-tokenizer
```

#### Langkah 3: Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verifikasi
composer --version
```

#### Langkah 4: Install Node.js

```bash
# Menggunakan NodeSource repository
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verifikasi
node --version
npm --version
```

#### Langkah 5: Install MySQL

```bash
sudo apt install -y mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql

# Amankan instalasi MySQL
sudo mysql_secure_installation
```

#### Langkah 6: Setup MySQL User

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE glowup_clinic;
CREATE USER 'glowup'@'localhost' IDENTIFIED BY 'password_aman';
GRANT ALL PRIVILEGES ON glowup_clinic.* TO 'glowup'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Langkah 7: Install Git

```bash
sudo apt install -y git
```

#### Langkah 8: Clone Repository

```bash
cd /var/www
sudo git clone https://github.com/username/clinic-glowup-web.git
cd clinic-glowup-web

# Set ownership
sudo chown -R $USER:www-data .
sudo chmod -R 775 storage bootstrap/cache
```

#### Langkah 9: Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
npm install
```

#### Langkah 10: Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glowup_clinic
DB_USERNAME=glowup
DB_PASSWORD=password_aman
```

#### Langkah 11: Migrasi dan Build

```bash
php artisan migrate --seed
npm run build
```

#### Langkah 12: Setup Nginx (Opsional untuk Development)

```bash
sudo apt install -y nginx
```

Buat konfigurasi:
```bash
sudo nano /etc/nginx/sites-available/glowup
```

```nginx
server {
    listen 80;
    server_name glowup.local;
    root /var/www/clinic-glowup-web/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/glowup /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

Tambah ke `/etc/hosts`:
```
127.0.0.1    glowup.local
```

#### Langkah 13: Akses Aplikasi

**Menggunakan PHP Built-in Server:**
```bash
php artisan serve
# Akses: http://localhost:8000
```

**Menggunakan Nginx:**
```
http://glowup.local
```

---

## 5. Konfigurasi Aplikasi

### 5.1 File .env

Berikut konfigurasi penting di file `.env`:

```env
# Aplikasi
APP_NAME="GlowUp Clinic"
APP_ENV=local
APP_KEY=base64:xxxxx
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glowup_clinic
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync

# Mail (untuk OTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@glowup.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5.2 Setup Storage Link

```bash
php artisan storage:link
```

Ini akan membuat symbolic link dari `public/storage` ke `storage/app/public`.

### 5.3 Cache Configuration (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5.4 Setup Cron (untuk Scheduled Tasks)

Tambahkan ke crontab:
```bash
crontab -e
```

```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Menjalankan Aplikasi

### 6.1 Development Mode

```bash
# Terminal 1: Laravel Development Server
php artisan serve

# Terminal 2: Vite Development Server (hot reload)
npm run dev
```

Akses: `http://localhost:8000`

### 6.2 Production Mode

```bash
# Build assets untuk production
npm run build

# Jalankan dengan web server (Nginx/Apache)
# atau menggunakan Laravel Octane untuk performance
```

### 6.3 Login Default

Setelah menjalankan seeder, gunakan kredensial:

| Role | Email | Password |
|------|-------|----------|
| Owner | owner@glowup.com | password |
| Admin | admin@glowup.com | password |
| Beautician | beautician@glowup.com | password |

---

## 7. Troubleshooting

### 7.1 Permission Issues (Linux/macOS)

```bash
# Fix storage permissions
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 7.2 Composer Memory Limit

```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### 7.3 NPM Build Errors

```bash
# Clear NPM cache
npm cache clean --force
rm -rf node_modules
rm package-lock.json
npm install
npm run build
```

### 7.4 Database Connection Refused

1. Pastikan MySQL/MariaDB running:
```bash
# Linux
sudo systemctl status mysql

# macOS
brew services list
```

2. Cek kredensial di `.env`

3. Test koneksi:
```bash
php artisan db:show
```

### 7.5 PHP Extension Missing

**Windows (Laragon):**
Edit `php.ini` dan uncomment extension yang dibutuhkan

**macOS:**
```bash
brew install php@8.3-gd
# atau
pecl install imagick
```

**Linux:**
```bash
sudo apt install php8.3-gd php8.3-imagick
```

### 7.6 Vite Manifest Not Found

```bash
npm run build
# atau untuk development
npm run dev
```

### 7.7 Class Not Found

```bash
composer dump-autoload
php artisan clear-compiled
```

### 7.8 Cache Issues

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 7.9 Migration Errors

```bash
# Fresh migration (WARNING: hapus semua data)
php artisan migrate:fresh --seed

# Rollback terakhir
php artisan migrate:rollback
```

---

## Quick Start Commands

```bash
# Clone repository
git clone https://github.com/username/clinic-glowup-web.git
cd clinic-glowup-web

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Edit .env untuk database connection
# ...

# Setup database
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Run application
php artisan serve
```

---

## Catatan Tambahan

1. **Untuk development**, gunakan `npm run dev` agar perubahan CSS/JS langsung terlihat
2. **Untuk production**, selalu gunakan `npm run build` dan aktifkan caching
3. **Backup database** secara regular sebelum melakukan update
4. **Jangan lupa** menjalankan `php artisan migrate` setelah pull update terbaru

Jika mengalami masalah yang tidak tercantum di atas, silakan:
1. Cek log di `storage/logs/laravel.log`
2. Aktifkan `APP_DEBUG=true` untuk melihat error detail
3. Hubungi tim support
