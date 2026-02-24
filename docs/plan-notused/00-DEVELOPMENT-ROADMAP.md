# GlowUp Development Roadmap

**Tech Stack:** Laravel 12 + Alpine.js + Tailwind CSS 4
**Created:** January 2026

---

## Ringkasan Project

GlowUp adalah sistem manajemen klinik kecantikan dengan fitur:
- Booking Management
- Customer & Skin Profile Management
- Treatment Records dengan foto Before/After
- Package Management
- POS / Checkout
- Reports & Analytics

---

## Development Phases

| Phase | Deskripsi | Priority |
|-------|-----------|----------|
| Phase 1 | Foundation & Auth | P0 |
| Phase 2 | Core Modules (Customer, Service, Appointment) | P0 |
| Phase 3 | Treatment & Package | P1 |
| Phase 4 | POS & Reports | P1 |
| Phase 5 | Polish & Deployment | P2 |

---

## File Plan Lainnya

1. `01-DESIGN-SYSTEM.md` - Color palette, typography, komponen
2. `02-PHASE1-FOUNDATION.md` - Setup Laravel, Auth, Dashboard
3. `03-PHASE2-CORE-MODULES.md` - Customer, Service, Appointment
4. `04-PHASE3-TREATMENT-PACKAGE.md` - Treatment Record, Package
5. `05-PHASE4-POS-REPORTS.md` - Checkout, Pembayaran, Laporan
6. `06-PHASE5-POLISH-DEPLOY.md` - Settings, Testing, Deployment

---

## Quick Reference

### Artisan Commands yang Sering Dipakai

```bash
# Create Model with Migration, Factory, Seeder
php artisan make:model Customer -mfs

# Create Controller with Resource
php artisan make:controller CustomerController --resource

# Create Form Request
php artisan make:request StoreCustomerRequest

# Run Migrations
php artisan migrate

# Run Seeders
php artisan db:seed

# Clear Cache
php artisan optimize:clear
```

### Folder Structure

```
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Services/
└── View/Components/

resources/
├── views/
│   ├── layouts/
│   ├── components/
│   ├── auth/
│   ├── dashboard/
│   ├── customers/
│   ├── services/
│   ├── appointments/
│   ├── treatments/
│   ├── packages/
│   ├── transactions/
│   ├── reports/
│   └── settings/
├── css/
└── js/

database/
├── migrations/
├── factories/
└── seeders/
```
