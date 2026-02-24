# Phase 5: Settings, Polish & Deployment

**Priority:** P2 (Nice to Have / Final)
**Estimasi:** Week 4-5

---

## Module A: Settings

### A.1 Controller

- [ ] **Create SettingController**
  ```bash
  php artisan make:controller SettingController
  ```

  Methods:
  - index() - Settings page with tabs
  - updateClinic(Request) - Update clinic profile
  - updateOperatingHours(Request) - Update jam operasional
  - updateUsers() - Manage staff/users

### A.2 Views

- [ ] **Settings Page** (resources/views/settings/index.blade.php)

  Tab navigation dengan Alpine.js:
  1. **Clinic Profile** - Informasi klinik
  2. **Operating Hours** - Jam buka
  3. **Users** - Manage staff
  4. **Preferences** - Pengaturan lainnya

### A.3 Tab: Clinic Profile

```
┌─────────────────────────────────────────────────────────────┐
│ Clinic Profile                                              │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Logo                                                        │
│ ┌──────────────┐                                           │
│ │              │  [Upload Logo]                            │
│ │   📷 Logo    │  Recommended: 200x200px, PNG/JPG          │
│ │              │                                           │
│ └──────────────┘                                           │
│                                                             │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Clinic Name         │  │ Phone               │           │
│ │ [Glow Aesthetic  ]  │  │ [021-1234-5678   ]  │           │
│ └─────────────────────┘  └─────────────────────┘           │
│                                                             │
│ ┌─────────────────────┐  ┌─────────────────────┐           │
│ │ Email               │  │ Website (optional)  │           │
│ │ [hello@glow.com  ]  │  │ [www.glowclinic.com]│           │
│ └─────────────────────┘  └─────────────────────┘           │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Address                                                 ││
│ │ [Jl. Sudirman No. 123, Jakarta Selatan 12345        ]  ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│                                              [Save Changes] │
└─────────────────────────────────────────────────────────────┘
```

### A.4 Tab: Operating Hours

```
┌─────────────────────────────────────────────────────────────┐
│ Operating Hours                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌───────────┬───────────┬───────────┬─────────────────────┐│
│ │ Day       │ Open      │ Close     │ Status              ││
│ ├───────────┼───────────┼───────────┼─────────────────────┤│
│ │ Sunday    │ --:--     │ --:--     │ ☐ Closed           ││
│ │ Monday    │ [09:00]   │ [18:00]   │ ☑ Open             ││
│ │ Tuesday   │ [09:00]   │ [18:00]   │ ☑ Open             ││
│ │ Wednesday │ [09:00]   │ [18:00]   │ ☑ Open             ││
│ │ Thursday  │ [09:00]   │ [18:00]   │ ☑ Open             ││
│ │ Friday    │ [09:00]   │ [18:00]   │ ☑ Open             ││
│ │ Saturday  │ [09:00]   │ [15:00]   │ ☑ Open             ││
│ └───────────┴───────────┴───────────┴─────────────────────┘│
│                                                             │
│ Appointment Slot Duration: [30 minutes ▼]                   │
│                                                             │
│                                              [Save Changes] │
└─────────────────────────────────────────────────────────────┘
```

### A.5 Tab: Users / Staff

```
┌─────────────────────────────────────────────────────────────┐
│ Staff Management                              [+ Add Staff] │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Avatar │ Name          │ Email          │ Role   │ Act  ││
│ │────────┼───────────────┼────────────────┼────────┼──────││
│ │  👤    │ Dr. Sarah     │ sarah@glow.com │ Owner  │ -    ││
│ │  👤    │ Admin One     │ admin@glow.com │ Admin  │ ✏️ 🗑️││
│ │  👤    │ Maya          │ maya@glow.com  │ Beauty │ ✏️ 🗑️││
│ │  👤    │ Lisa          │ lisa@glow.com  │ Beauty │ ✏️ 🗑️││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Role Permissions:                                           │
│ • Owner: Full access                                        │
│ • Admin: Manage booking, customer, transaction              │
│ • Beautician: View schedule, create treatment record        │
└─────────────────────────────────────────────────────────────┘
```

- [ ] **Add/Edit Staff Modal**
  - Name
  - Email
  - Phone
  - Role dropdown
  - Password (for new) / Reset password button
  - Is Active toggle

---

## Module B: UI/UX Polish

### B.1 Responsive Design

- [ ] **Mobile sidebar** - Overlay dengan backdrop
- [ ] **Mobile tables** - Horizontal scroll atau card view
- [ ] **Touch-friendly** - Larger tap targets
- [ ] **Mobile forms** - Stack inputs vertically

### B.2 Loading States

- [ ] **Button loading** - Spinner + disabled state
  ```html
  <button x-data="{ loading: false }"
          @click="loading = true"
          :disabled="loading"
          class="...">
      <span x-show="!loading">Save</span>
      <span x-show="loading" class="flex items-center">
          <svg class="animate-spin w-4 h-4 mr-2">...</svg>
          Saving...
      </span>
  </button>
  ```

- [ ] **Page loading** - Skeleton loaders untuk data
- [ ] **Form submission** - Disable form saat submit

### B.3 Notifications & Alerts

- [ ] **Toast notifications** (Alpine.js)
  ```html
  <div x-data="{ toasts: [] }"
       @notify.window="toasts.push($event.detail)"
       class="fixed bottom-4 right-4 space-y-2">
      <template x-for="toast in toasts" :key="toast.id">
          <div class="bg-white rounded-xl shadow-lg p-4 flex items-center space-x-3">
              <!-- Success/Error icon -->
              <span x-text="toast.message"></span>
          </div>
      </template>
  </div>
  ```

- [ ] **Flash messages** dari session
- [ ] **Confirmation dialogs** untuk delete actions

### B.4 Form Improvements

- [ ] **Validation feedback** - Real-time dengan Alpine
- [ ] **Auto-save drafts** - LocalStorage (optional)
- [ ] **Keyboard shortcuts** - Escape to close modal, Enter to submit

### B.5 Empty States

- [ ] **No customers** - Illustration + "Add first customer" CTA
- [ ] **No appointments today** - Friendly message
- [ ] **No transactions** - Empty state dengan tips

### B.6 Dark Mode (Optional)

- [ ] **Color scheme** - Dark variants
- [ ] **Toggle** - System preference atau manual
- [ ] **Persist preference** - LocalStorage

---

## Module C: Testing

### C.1 Feature Tests

- [ ] **AuthTest**
  - test_user_can_login_with_valid_credentials
  - test_user_cannot_login_with_invalid_credentials
  - test_inactive_user_cannot_login
  - test_user_can_logout

- [ ] **CustomerTest**
  - test_can_create_customer
  - test_cannot_create_customer_with_duplicate_phone
  - test_can_update_customer
  - test_can_delete_customer
  - test_can_search_customer

- [ ] **AppointmentTest**
  - test_can_create_appointment
  - test_cannot_double_book_same_time
  - test_can_update_appointment_status
  - test_can_cancel_appointment

- [ ] **TransactionTest**
  - test_can_create_transaction
  - test_discount_calculation_correct
  - test_invoice_number_generated
  - test_change_calculated_for_cash_payment

### C.2 Browser Tests (Optional - Dusk)

- [ ] Setup Laravel Dusk
- [ ] Login flow test
- [ ] Create appointment flow test
- [ ] Checkout flow test

### C.3 Run Tests

```bash
# Run all tests
php artisan test --compact

# Run specific test file
php artisan test --filter=CustomerTest

# Run with coverage (requires Xdebug)
php artisan test --coverage
```

---

## Module D: Security

### D.1 Authentication

- [ ] **Rate limiting** - Login attempts
  ```php
  // bootstrap/app.php
  ->withMiddleware(function (Middleware $middleware) {
      $middleware->throttleApi('60,1');
  })
  ```

- [ ] **Session timeout** - Auto logout setelah inactive
- [ ] **Secure cookies** - Set di .env production

### D.2 Authorization

- [ ] **Policy classes** untuk setiap model
  ```bash
  php artisan make:policy CustomerPolicy --model=Customer
  ```

- [ ] **Gate checks** di controller
  ```php
  $this->authorize('update', $customer);
  ```

### D.3 Input Validation

- [ ] **XSS prevention** - Blade auto-escaping
- [ ] **CSRF protection** - Already built-in
- [ ] **SQL injection** - Using Eloquent

### D.4 File Upload Security

- [ ] **Validate file types** - Only images
- [ ] **Limit file size** - Max 2MB
- [ ] **Rename files** - Random names, not user input

---

## Module E: Performance

### E.1 Database

- [ ] **Indexes** - Add untuk frequently queried columns
- [ ] **Eager loading** - Prevent N+1 queries
  ```php
  Appointment::with(['customer', 'service', 'staff'])->get();
  ```

- [ ] **Query optimization** - Review slow queries

### E.2 Caching

- [ ] **Config cache**
  ```bash
  php artisan config:cache
  ```

- [ ] **Route cache**
  ```bash
  php artisan route:cache
  ```

- [ ] **View cache**
  ```bash
  php artisan view:cache
  ```

### E.3 Assets

- [ ] **Minify CSS/JS** - Vite production build
- [ ] **Image optimization** - Compress uploaded images
- [ ] **Lazy loading** - Images below fold

---

## Module F: Deployment

### F.1 Server Requirements

- PHP 8.3+
- MySQL 8.0+
- Nginx atau Apache
- Composer
- Node.js (untuk build assets)
- SSL Certificate

### F.2 Deployment Checklist

- [ ] **Configure .env**
  ```env
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://yourdomain.com

  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_DATABASE=glowup_clinic
  DB_USERNAME=production_user
  DB_PASSWORD=secure_password

  CACHE_DRIVER=file
  SESSION_DRIVER=file
  QUEUE_CONNECTION=sync
  ```

- [ ] **Install dependencies**
  ```bash
  composer install --optimize-autoloader --no-dev
  npm install
  npm run build
  ```

- [ ] **Generate key**
  ```bash
  php artisan key:generate
  ```

- [ ] **Run migrations**
  ```bash
  php artisan migrate --force
  ```

- [ ] **Run seeders** (only if fresh install)
  ```bash
  php artisan db:seed --force
  ```

- [ ] **Storage link**
  ```bash
  php artisan storage:link
  ```

- [ ] **Cache configs**
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

- [ ] **Set permissions**
  ```bash
  chmod -R 775 storage bootstrap/cache
  chown -R www-data:www-data storage bootstrap/cache
  ```

### F.3 Nginx Config

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    root /var/www/glowup/public;

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

### F.4 SSL Setup

```bash
# Using Certbot
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### F.5 Backup Script

```bash
#!/bin/bash
# /scripts/backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups"
DB_NAME="glowup_clinic"

# Database backup
mysqldump -u root -p$DB_PASSWORD $DB_NAME | gzip > $BACKUP_DIR/db_${DATE}.sql.gz

# Files backup
tar -czf $BACKUP_DIR/files_${DATE}.tar.gz /var/www/glowup/storage/app/public

# Keep only last 7 days
find $BACKUP_DIR -name "*.gz" -mtime +7 -delete
```

### F.6 Cron Jobs

```bash
# /etc/crontab
* * * * * www-data cd /var/www/glowup && php artisan schedule:run >> /dev/null 2>&1

# Daily backup at 2 AM
0 2 * * * root /scripts/backup.sh >> /var/log/backup.log 2>&1
```

---

## Post-Launch

### Monitoring

- [ ] Setup error logging (Laravel Log atau Sentry)
- [ ] Monitor server resources
- [ ] Setup uptime monitoring

### Documentation

- [ ] User manual untuk staff
- [ ] Admin guide untuk owner
- [ ] API documentation (if needed)

### Training

- [ ] Training session untuk owner
- [ ] Training session untuk admin/staff
- [ ] Provide support contact

---

## Files Summary

```
app/
├── Http/Controllers/
│   └── SettingController.php
├── Policies/
│   ├── CustomerPolicy.php
│   ├── AppointmentPolicy.php
│   └── TransactionPolicy.php
└── Providers/
    └── AuthServiceProvider.php

resources/views/
├── settings/
│   └── index.blade.php
└── components/
    ├── toast.blade.php
    ├── empty-state.blade.php
    └── loading-button.blade.php

tests/
├── Feature/
│   ├── AuthTest.php
│   ├── CustomerTest.php
│   ├── AppointmentTest.php
│   └── TransactionTest.php
└── Unit/
    └── ...

config/
scripts/
└── backup.sh
```
