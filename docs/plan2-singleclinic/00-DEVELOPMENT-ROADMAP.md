# GlowUp Single Clinic - Development Roadmap

**Model Bisnis:** Custom Solution (bukan SaaS)
**Tech Stack:** Laravel 12 + Alpine.js + Tailwind CSS 4

---

## Overview

GlowUp adalah sistem manajemen klinik kecantikan yang dijual sebagai **solusi custom** untuk single clinic.
Klien mendapatkan:
- Website booking custom dengan domain sendiri
- Dashboard admin untuk manajemen operasional
- Mobile apps (future)
- Support & maintenance

---

## Development Phases

### Phase 1: Foundation & Landing Page (DONE)
- [x] Setup Laravel 12 project
- [x] Configure Tailwind CSS 4 dengan custom theme
- [x] Setup Alpine.js
- [x] Landing page untuk marketing (bukan SaaS)
- [x] Design system (colors, typography, components)

### Phase 2: Authentication & Dashboard
- [ ] Login/Logout system
- [ ] Role-based access (Owner, Admin, Beautician)
- [ ] Dashboard layout dengan sidebar
- [ ] Dashboard overview (stats, charts, today's appointments)

### Phase 3: Master Data
- [ ] Service Categories (CRUD)
- [ ] Services (CRUD dengan kategori)
- [ ] Staff/User management
- [ ] Clinic settings (profile, logo, operating hours)

### Phase 4: Customer Management
- [ ] Customer list dengan search & filter
- [ ] Customer form (basic info + skin profile)
- [ ] Customer detail view
- [ ] Customer history (visits, spending)

### Phase 5: Appointment System
- [ ] Appointment calendar view (daily/weekly)
- [ ] Create booking (select customer, service, date/time, staff)
- [ ] Update appointment status (pending → confirmed → in progress → completed)
- [ ] Walk-in handling

### Phase 6: Treatment Records
- [ ] Create treatment record dari appointment
- [ ] Upload foto before/after
- [ ] Notes, products used, recommendations
- [ ] Treatment history per customer

### Phase 7: Package Management
- [ ] Create packages (bundle services)
- [ ] Sell package to customer
- [ ] Track sessions (used/remaining)
- [ ] Redeem session from package

### Phase 8: POS & Checkout
- [ ] Checkout dari completed appointment
- [ ] Add items (services, products, packages)
- [ ] Apply discount
- [ ] Payment processing (cash, QRIS, transfer, card)
- [ ] Generate & print receipt

### Phase 9: Reports
- [ ] Revenue report (daily, weekly, monthly)
- [ ] Service popularity report
- [ ] Customer report (new, returning, top spenders)
- [ ] Staff performance report
- [ ] Export to PDF/Excel

### Phase 10: Polish & Deployment
- [ ] UI/UX refinements
- [ ] Testing & bug fixes
- [ ] Server setup & deployment
- [ ] Documentation
- [ ] Training materials

### Phase 11: Mobile API (DONE)
- [x] Setup Laravel Sanctum untuk API authentication
- [x] Create API routes dengan versioning (v1)
- [x] Auth endpoints (login, logout, profile)
- [x] Customer API (CRUD + stats, treatments, packages, appointments)
- [x] Service & Category API
- [x] Appointment API (CRUD + calendar, available slots, status update)
- [x] Treatment Record API (CRUD dengan photo upload)
- [x] Package API (list packages, customer packages)
- [x] Transaction API (list, detail, receipt)
- [x] API Documentation

---

## File References

- Main Documentation: `docs/GLOWUP_SINGLE_CLINIC_DOCUMENTATION.md`
- Design System: `docs/plan2-singleclinic/01-DESIGN-SYSTEM.md`
- Phase Details: `docs/plan2-singleclinic/02-PHASE2-AUTH-DASHBOARD.md` dst
- **Mobile API Documentation: `docs/plan2-singleclinic/07-PHASE11-MOBILE-API.md`**

---

## Tech Decisions

| Area | Technology | Notes |
|------|------------|-------|
| Backend | Laravel 12 | Full-stack framework |
| Frontend | Blade + Alpine.js | Server-rendered dengan interactivity |
| CSS | Tailwind CSS 4 | Utility-first, custom theme |
| Database | MySQL 8 | Standard relational DB |
| Auth (Web) | Laravel built-in | Session-based authentication |
| Auth (API) | Laravel Sanctum | Token-based authentication |
| Charts | Chart.js | Lightweight charting |
| Calendar | FullCalendar | Appointment calendar |
| File Storage | Local/S3 | For photos |

---

## Current Status

**Phase:** Phase 2 (Authentication & Dashboard)
**Last Updated:** January 2026
