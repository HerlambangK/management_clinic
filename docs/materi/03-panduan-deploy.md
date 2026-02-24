# Panduan Deploy GlowUp Clinic

## Daftar Isi
1. [Persiapan Deployment](#1-persiapan-deployment)
2. [Deploy ke VPS](#2-deploy-ke-vps)
3. [Deploy ke Shared Hosting](#3-deploy-ke-shared-hosting)
4. [Konfigurasi SSL](#4-konfigurasi-ssl)
5. [Optimasi Production](#5-optimasi-production)
6. [Maintenance & Monitoring](#6-maintenance--monitoring)
7. [Backup & Recovery](#7-backup--recovery)

---

## 1. Persiapan Deployment

### 1.1 Checklist Sebelum Deploy

- [ ] Semua fitur sudah ditest
- [ ] Environment production sudah disiapkan
- [ ] Domain sudah diarahkan ke server
- [ ] SSL certificate sudah ready
- [ ] Backup strategy sudah ditentukan
- [ ] Monitoring sudah disiapkan

### 1.2 Persiapan File .env Production

```env
# Application
APP_NAME="GlowUp Clinic"
APP_ENV=production
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glowup_production
DB_USERNAME=glowup_user
DB_PASSWORD=strong_password_here

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail (Production)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_mailgun_username
MAIL_PASSWORD=your_mailgun_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 1.3 Build Assets untuk Production

```bash
# Di local machine
npm run build

# Commit hasil build
git add public/build
git commit -m "Build assets for production"
git push origin main
```

---

## 2. Deploy ke VPS

### 2.1 Rekomendasi VPS Provider

| Provider | Minimum Plan | Harga/bulan |
|----------|--------------|-------------|
| DigitalOcean | 1GB RAM, 1 vCPU | $6 |
| Vultr | 1GB RAM, 1 vCPU | $6 |
| Linode | 1GB RAM, 1 vCPU | $5 |
| AWS Lightsail | 1GB RAM, 1 vCPU | $5 |
| Contabo | 4GB RAM, 2 vCPU | $5.50 |
| IDCloudHost | 1GB RAM, 1 vCPU | Rp 50.000 |

### 2.2 Setup VPS (Ubuntu 22.04/24.04)

#### Langkah 1: Initial Server Setup

```bash
# Login ke server
ssh root@your_server_ip

# Update system
apt update && apt upgrade -y

# Buat user baru (hindari menggunakan root)
adduser deploy
usermod -aG sudo deploy

# Setup SSH key untuk user deploy
su - deploy
mkdir -p ~/.ssh
chmod 700 ~/.ssh
# Copy public key Anda ke ~/.ssh/authorized_keys

# Disable root login dan password authentication
sudo nano /etc/ssh/sshd_config
# Set: PermitRootLogin no
# Set: PasswordAuthentication no
sudo systemctl restart sshd
```

#### Langkah 2: Install Required Software

```bash
# Install dependencies
sudo apt install -y software-properties-common curl git unzip

# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.3
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-xml \
    php8.3-curl php8.3-gd php8.3-mbstring php8.3-zip php8.3-bcmath \
    php8.3-intl php8.3-readline php8.3-tokenizer

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL
sudo apt install -y mysql-server
sudo mysql_secure_installation

# Install Nginx
sudo apt install -y nginx
sudo systemctl enable nginx
```

#### Langkah 3: Setup MySQL Database

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE glowup_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'glowup_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON glowup_production.* TO 'glowup_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Langkah 4: Clone dan Setup Aplikasi

```bash
# Buat direktori aplikasi
sudo mkdir -p /var/www/glowup
sudo chown deploy:www-data /var/www/glowup

# Clone repository
cd /var/www/glowup
git clone https://github.com/username/clinic-glowup-web.git .

# Set permissions
sudo chown -R deploy:www-data .
sudo chmod -R 775 storage bootstrap/cache

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Setup environment
cp .env.example .env
nano .env  # Edit sesuai konfigurasi production

# Generate key dan setup
php artisan key:generate
php artisan storage:link
php artisan migrate --force --seed
```

#### Langkah 5: Konfigurasi Nginx

```bash
sudo nano /etc/nginx/sites-available/glowup
```

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/glowup/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private auth;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml application/javascript;

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
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|woff|woff2)$ {
        expires 1M;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.(env|git|htaccess) {
        deny all;
    }

    client_max_body_size 20M;
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/glowup /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default

# Test dan restart
sudo nginx -t
sudo systemctl restart nginx
```

#### Langkah 6: Konfigurasi PHP-FPM

```bash
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
```

Edit:
```ini
user = deploy
group = www-data
listen.owner = deploy
listen.group = www-data

pm = dynamic
pm.max_children = 10
pm.start_servers = 3
pm.min_spare_servers = 2
pm.max_spare_servers = 5
pm.max_requests = 500
```

```bash
sudo systemctl restart php8.3-fpm
```

#### Langkah 7: Setup Queue Worker (Opsional)

Buat service untuk queue worker:

```bash
sudo nano /etc/systemd/system/glowup-worker.service
```

```ini
[Unit]
Description=GlowUp Queue Worker
After=network.target

[Service]
User=deploy
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/glowup/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable glowup-worker
sudo systemctl start glowup-worker
```

#### Langkah 8: Setup Cron untuk Scheduler

```bash
crontab -e
```

Tambahkan:
```cron
* * * * * cd /var/www/glowup && php artisan schedule:run >> /dev/null 2>&1
```

---

### 2.3 Deploy dengan Laravel Forge (Premium)

Laravel Forge adalah layanan managed deployment yang mempermudah proses deploy.

1. Daftar di [forge.laravel.com](https://forge.laravel.com)
2. Connect ke VPS provider Anda
3. Provision server baru
4. Add site dengan repository GitHub
5. Deploy otomatis

**Keuntungan:**
- Automated deployment
- SSL otomatis
- Queue worker management
- Database backup
- Monitoring built-in

---

### 2.4 Deploy Script (Automated)

Buat file `deploy.sh` di server:

```bash
#!/bin/bash
set -e

echo "Starting deployment..."

cd /var/www/glowup

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets (jika tidak di-commit)
# npm install
# npm run build

# Restart queue workers
sudo systemctl restart glowup-worker

# Set permissions
sudo chown -R deploy:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "Deployment completed!"
```

```bash
chmod +x deploy.sh
```

---

## 3. Deploy ke Shared Hosting

### 3.1 Rekomendasi Shared Hosting

| Provider | Fitur | Harga/bulan |
|----------|-------|-------------|
| Niagahoster | PHP 8.3, MySQL, SSL | Rp 20.000 |
| Domainesia | PHP 8.3, MySQL, SSL | Rp 15.000 |
| Hostinger | PHP 8.3, MySQL, SSL | Rp 25.000 |
| Dewaweb | PHP 8.3, MySQL, SSL | Rp 30.000 |

**Persyaratan Minimum:**
- PHP 8.2 atau 8.3
- MySQL 8.0+
- SSH Access (sangat direkomendasikan)
- Composer Support
- Cron Job Support

### 3.2 Metode 1: Upload via SSH (Rekomendasi)

#### Langkah 1: Persiapan di Local

```bash
# Build assets
npm run build

# Buat zip untuk upload (exclude node_modules & vendor)
zip -r glowup.zip . -x "node_modules/*" -x "vendor/*" -x ".git/*"
```

#### Langkah 2: Upload dan Extract

```bash
# SSH ke hosting
ssh username@yourdomain.com

# Upload file (via SCP dari local)
scp glowup.zip username@yourdomain.com:~/

# Di server, pindah ke folder dan extract
cd ~/
unzip glowup.zip -d glowup_temp
```

#### Langkah 3: Setup Struktur Folder

Shared hosting biasanya punya struktur:
```
/home/username/
├── public_html/          # Document root
└── glowup/               # Application files
```

**Pindahkan file:**
```bash
# Buat folder aplikasi
mkdir -p ~/glowup

# Pindahkan semua file kecuali public
mv ~/glowup_temp/* ~/glowup/

# Pindahkan isi public ke public_html
cp -r ~/glowup/public/* ~/public_html/

# Edit index.php di public_html
nano ~/public_html/index.php
```

**Edit `~/public_html/index.php`:**
```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Tentukan path ke aplikasi Laravel
if (file_exists($maintenance = __DIR__.'/../glowup/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../glowup/vendor/autoload.php';

$app = require_once __DIR__.'/../glowup/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

#### Langkah 4: Install Dependencies

```bash
cd ~/glowup
composer install --no-dev --optimize-autoloader
```

#### Langkah 5: Setup Environment

```bash
cp .env.example .env
nano .env
```

Edit sesuai kredensial hosting:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=localhost
DB_DATABASE=username_glowup
DB_USERNAME=username_glowup
DB_PASSWORD=database_password
```

#### Langkah 6: Generate Key dan Migrate

```bash
php artisan key:generate
php artisan migrate --force --seed
php artisan storage:link
```

**Catatan:** Untuk storage link di shared hosting, mungkin perlu manual:
```bash
# Di public_html
ln -s ~/glowup/storage/app/public storage
```

#### Langkah 7: Set Permissions

```bash
chmod -R 755 ~/glowup
chmod -R 775 ~/glowup/storage
chmod -R 775 ~/glowup/bootstrap/cache
```

---

### 3.3 Metode 2: Upload via cPanel File Manager

#### Langkah 1: Persiapan

```bash
# Di local, build dan compress
npm run build
composer install --no-dev

# Compress semua file
zip -r glowup.zip .
```

#### Langkah 2: Upload via cPanel

1. Login ke cPanel
2. Buka File Manager
3. Navigate ke folder home (di luar public_html)
4. Upload `glowup.zip`
5. Extract file

#### Langkah 3: Setup public_html

1. Pindahkan isi folder `public` ke `public_html`
2. Edit `public_html/index.php`:

```php
<?php
// Ubah path ke lokasi aplikasi yang benar
require __DIR__.'/../glowup/vendor/autoload.php';
$app = require_once __DIR__.'/../glowup/bootstrap/app.php';
// ... sisanya sama
```

#### Langkah 4: Setup Database via cPanel

1. Buka MySQL Databases di cPanel
2. Create New Database: `username_glowup`
3. Create New User: `username_glowupuser`
4. Add User to Database dengan ALL PRIVILEGES

#### Langkah 5: Setup Environment

1. Di File Manager, navigate ke folder `glowup`
2. Copy `.env.example` menjadi `.env`
3. Edit `.env` dengan kredensial database

#### Langkah 6: Jalankan Artisan via Terminal cPanel

1. Buka Terminal di cPanel
2. Jalankan:
```bash
cd ~/glowup
php artisan key:generate
php artisan migrate --force --seed
```

#### Langkah 7: Setup Cron Job

1. Buka Cron Jobs di cPanel
2. Tambah cron baru:
   - Minute: * (every minute)
   - Command: `cd ~/glowup && php artisan schedule:run >> /dev/null 2>&1`

---

### 3.4 Konfigurasi .htaccess

Pastikan `public_html/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Disable directory browsing
Options -Indexes

# Block access to sensitive files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# PHP settings (jika diizinkan)
<IfModule mod_php.c>
    php_value upload_max_filesize 20M
    php_value post_max_size 20M
    php_value max_execution_time 300
    php_value memory_limit 256M
</IfModule>
```

---

## 4. Konfigurasi SSL

### 4.1 SSL dengan Let's Encrypt (VPS)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Generate certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal (otomatis ditambahkan)
sudo certbot renew --dry-run
```

### 4.2 SSL di Shared Hosting

Kebanyakan shared hosting menyediakan SSL gratis via cPanel:

1. Login ke cPanel
2. Buka SSL/TLS Status atau Let's Encrypt
3. Pilih domain dan Install
4. Aktifkan Force HTTPS

### 4.3 Update .env untuk HTTPS

```env
APP_URL=https://yourdomain.com
```

### 4.4 Force HTTPS via Middleware

Laravel otomatis handle HTTPS jika sudah dikonfigurasi dengan benar. Pastikan `TrustProxies` middleware aktif untuk load balancer.

---

## 5. Optimasi Production

### 5.1 Laravel Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 5.2 PHP-FPM Tuning (VPS)

Edit `/etc/php/8.3/fpm/php.ini`:

```ini
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 120
max_input_time = 120

opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1
```

### 5.3 MySQL Tuning

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_flush_log_at_trx_commit = 2
query_cache_type = 1
query_cache_size = 32M
```

### 5.4 Nginx Caching

Tambahkan di konfigurasi Nginx:

```nginx
# FastCGI cache
fastcgi_cache_path /var/cache/nginx levels=1:2 keys_zone=LARAVEL:100m inactive=60m;
fastcgi_cache_key "$scheme$request_method$host$request_uri";

server {
    # ... konfigurasi lainnya ...

    location ~ \.php$ {
        # ... konfigurasi PHP-FPM ...

        # Enable cache untuk static pages
        fastcgi_cache LARAVEL;
        fastcgi_cache_valid 200 60m;
        fastcgi_cache_use_stale error timeout invalid_header http_500;
        add_header X-Cache $upstream_cache_status;
    }
}
```

---

## 6. Maintenance & Monitoring

### 6.1 Maintenance Mode

```bash
# Aktifkan maintenance mode
php artisan down --secret="secret-key-untuk-akses"

# Akses selama maintenance: https://yourdomain.com/secret-key-untuk-akses

# Nonaktifkan maintenance mode
php artisan up
```

### 6.2 Log Management

```bash
# Lihat log realtime
tail -f storage/logs/laravel.log

# Rotate logs (sudah dikonfigurasi dengan LOG_CHANNEL=daily)
# Logs akan otomatis di-rotate setiap hari
```

### 6.3 Monitoring dengan Laravel Telescope (Development/Staging)

```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### 6.4 Uptime Monitoring

Gunakan layanan gratis:
- [UptimeRobot](https://uptimerobot.com/) - 50 monitors gratis
- [Freshping](https://www.freshworks.com/website-monitoring/) - 50 monitors gratis
- [HetrixTools](https://hetrixtools.com/) - 15 monitors gratis

### 6.5 Error Tracking

Rekomendasi:
- [Sentry](https://sentry.io/) - Free tier available
- [Bugsnag](https://www.bugsnag.com/) - Free tier available
- [Rollbar](https://rollbar.com/) - Free tier available

```bash
# Install Sentry
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=your-sentry-dsn
```

---

## 7. Backup & Recovery

### 7.1 Database Backup

#### Manual Backup
```bash
# Backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore
mysql -u username -p database_name < backup_file.sql
```

#### Automated Backup Script
```bash
#!/bin/bash
# backup.sh

BACKUP_DIR="/home/deploy/backups"
DB_NAME="glowup_production"
DB_USER="glowup_user"
DB_PASS="password"
DATE=$(date +%Y%m%d_%H%M%S)

# Buat direktori backup
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup files (storage)
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/glowup/storage/app

# Hapus backup lebih dari 7 hari
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

```bash
chmod +x backup.sh

# Tambah ke crontab untuk backup harian jam 2 pagi
crontab -e
# 0 2 * * * /home/deploy/backup.sh >> /home/deploy/backup.log 2>&1
```

### 7.2 File Backup

```bash
# Backup storage folder
tar -czvf storage_backup.tar.gz storage/app/public

# Backup seluruh aplikasi
tar -czvf app_backup.tar.gz /var/www/glowup --exclude='vendor' --exclude='node_modules'
```

### 7.3 Offsite Backup

Gunakan rclone untuk backup ke cloud storage:

```bash
# Install rclone
curl https://rclone.org/install.sh | sudo bash

# Konfigurasi (Google Drive, S3, dll)
rclone config

# Sync backup ke cloud
rclone sync /home/deploy/backups remote:glowup-backups
```

### 7.4 Recovery Plan

1. **Database Recovery:**
```bash
# Download backup terakhir
gunzip backup.sql.gz

# Restore database
mysql -u username -p database_name < backup.sql
```

2. **File Recovery:**
```bash
# Extract file backup
tar -xzvf storage_backup.tar.gz -C /var/www/glowup/
```

3. **Full Recovery:**
```bash
# Clone fresh dari repository
git clone https://github.com/username/clinic-glowup-web.git

# Restore .env
# Restore database
# Restore storage files
# Run migrations
php artisan migrate

# Rebuild caches
php artisan config:cache
php artisan route:cache
```

---

## 8. Security Checklist

### 8.1 Server Security

- [ ] Disable root SSH login
- [ ] Use SSH key authentication
- [ ] Setup firewall (UFW)
- [ ] Regular security updates
- [ ] Fail2ban for brute force protection

```bash
# Setup UFW
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable

# Install Fail2ban
sudo apt install fail2ban
sudo systemctl enable fail2ban
```

### 8.2 Application Security

- [ ] APP_DEBUG=false di production
- [ ] APP_ENV=production
- [ ] Strong database passwords
- [ ] HTTPS enforced
- [ ] CORS configured properly
- [ ] Rate limiting enabled
- [ ] Input validation active

### 8.3 Database Security

- [ ] Restricted database user privileges
- [ ] Strong passwords
- [ ] No remote access (bind to localhost)
- [ ] Regular backups
- [ ] Encrypted backups

---

## Quick Deploy Commands Summary

### VPS (Ubuntu)
```bash
# Initial setup
sudo apt update && sudo apt upgrade -y
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql nginx mysql-server git
composer global require laravel/installer

# Deploy
cd /var/www
git clone your-repo glowup
cd glowup
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env
php artisan key:generate
php artisan migrate --force --seed
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

### Shared Hosting
```bash
# Di local
npm run build
zip -r glowup.zip . -x "node_modules/*" -x ".git/*"

# Upload via SSH/FTP
# Setup index.php di public_html
# Setup .env
# Run migrations via SSH atau cPanel Terminal
```

---

## Catatan Penting

1. **Selalu backup** sebelum melakukan update atau perubahan besar
2. **Test di staging** sebelum deploy ke production
3. **Monitor logs** secara regular untuk mendeteksi masalah
4. **Update dependencies** secara berkala untuk security patches
5. **Dokumentasikan** setiap perubahan konfigurasi

Jika mengalami masalah, cek:
1. `storage/logs/laravel.log`
2. Nginx error log: `/var/log/nginx/error.log`
3. PHP-FPM log: `/var/log/php8.3-fpm.log`
