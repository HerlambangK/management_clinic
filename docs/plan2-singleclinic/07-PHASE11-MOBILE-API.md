# GlowUp Clinic - API Integration Documentation

Dokumentasi integrasi API antara Laravel Backend dan Mobile App.

**Base URL:** `https://your-domain.com/api/v1`

**Last Updated:** 2026-01-28

---

## Table of Contents
1. [Authentication](#1-authentication)
2. [Service Categories](#2-service-categories)
3. [Services](#3-services)
4. [Customers](#4-customers)
5. [Appointments](#5-appointments)
6. [Treatment Records](#6-treatment-records)
7. [Packages](#7-packages)
8. [Customer Packages](#8-customer-packages)
9. [Transactions](#9-transactions)
10. [Integration Status](#10-integration-status)
11. [Flutter Implementation Guide](#11-flutter-implementation-guide)

---

## 1. Authentication

### Login
```
POST /login
```
**Request Body:**
```json
{
  "email": "string (required)",
  "password": "string (required)",
  "device_name": "string (optional, default: mobile-app)"
}
```
**Response:**
```json
{
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin",
      "email": "admin@glowup.test",
      "phone": "081234567890",
      "role": "admin",
      "role_label": "Admin",
      "avatar": null,
      "is_active": true,
      "created_at": "2026-01-28T10:00:00.000000Z"
    },
    "token": "1|abc123xyz..."
  }
}
```
**Error Response (422):**
```json
{
  "message": "Kredensial yang diberikan tidak cocok dengan data kami.",
  "errors": {
    "email": ["Kredensial yang diberikan tidak cocok dengan data kami."]
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Logout
```
POST /logout
Authorization: Bearer {token}
```
**Response:**
```json
{
  "message": "Logout berhasil"
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Profile
```
GET /profile
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Admin",
    "email": "admin@glowup.test",
    "phone": "081234567890",
    "role": "admin",
    "role_label": "Admin",
    "avatar": null,
    "is_active": true,
    "created_at": "2026-01-28T10:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Update Profile
```
PUT /profile
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "name": "string (optional)",
  "phone": "string (optional)",
  "current_password": "string (required if changing password)",
  "new_password": "string (optional, min:8)",
  "new_password_confirmation": "string (required with new_password)"
}
```
**Response:**
```json
{
  "message": "Profil berhasil diperbarui",
  "data": {
    "id": 1,
    "name": "Admin Updated",
    "email": "admin@glowup.test",
    "phone": "081234567899",
    "role": "admin",
    "role_label": "Admin",
    "avatar": null,
    "is_active": true,
    "created_at": "2026-01-28T10:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

## 2. Service Categories

### List Service Categories
```
GET /service-categories
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| with_services | boolean | Include services in each category |
| with_count | boolean | Include services count |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Facial",
      "description": "Perawatan wajah",
      "icon": "💆",
      "sort_order": 1,
      "is_active": true,
      "services_count": 5,
      "services": [
        {
          "id": 1,
          "name": "Facial Brightening",
          "duration_minutes": 60,
          "price": 250000,
          "formatted_price": "Rp 250.000"
        }
      ],
      "created_at": "2026-01-28T10:00:00.000000Z"
    }
  ]
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Service Category Detail
```
GET /service-categories/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Facial",
    "description": "Perawatan wajah",
    "icon": "💆",
    "sort_order": 1,
    "is_active": true,
    "services": [
      {
        "id": 1,
        "name": "Facial Brightening",
        "duration_minutes": 60,
        "price": 250000
      }
    ],
    "created_at": "2026-01-28T10:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

## 3. Services

### List Services
```
GET /services
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| category_id | integer | Filter by category |
| search | string | Search by name |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "name": "Facial Brightening",
      "description": "Perawatan untuk mencerahkan kulit wajah",
      "duration_minutes": 60,
      "formatted_duration": "60 min",
      "price": 250000,
      "formatted_price": "Rp 250.000",
      "image": "services/facial-brightening.jpg",
      "is_active": true,
      "category": {
        "id": 1,
        "name": "Facial",
        "icon": "💆"
      },
      "created_at": "2026-01-28T10:00:00.000000Z"
    }
  ]
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Service Detail
```
GET /services/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "category_id": 1,
    "name": "Facial Brightening",
    "description": "Perawatan untuk mencerahkan kulit wajah",
    "duration_minutes": 60,
    "formatted_duration": "60 min",
    "price": 250000,
    "formatted_price": "Rp 250.000",
    "image": "services/facial-brightening.jpg",
    "is_active": true,
    "category": {
      "id": 1,
      "name": "Facial",
      "icon": "💆"
    },
    "created_at": "2026-01-28T10:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

## 4. Customers

### List Customers
```
GET /customers
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| search | string | Search by name, phone, or email |
| per_page | integer | Items per page (default: 15) |
| page | integer | Page number |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Rina Wijaya",
      "phone": "081234567890",
      "email": "rina@email.com",
      "birthdate": "1990-03-15",
      "age": 35,
      "gender": "female",
      "address": "Jl. Sudirman No. 123, Jakarta",
      "skin_type": "combination",
      "skin_concerns": ["acne", "large_pores"],
      "allergies": "AHA, Parfum",
      "notes": "Customer VIP",
      "total_visits": 24,
      "total_spent": 12500000,
      "formatted_total_spent": "Rp 12.500.000",
      "last_visit": "2026-01-26",
      "created_at": "2026-01-01T10:00:00.000000Z",
      "updated_at": "2026-01-28T10:00:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/v1/customers?page=1",
    "last": "http://localhost/api/v1/customers?page=10",
    "prev": null,
    "next": "http://localhost/api/v1/customers?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Create Customer
```
POST /customers
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "phone": "string (required, max:20, unique)",
  "email": "email (optional, max:255)",
  "birthdate": "date (optional, format:Y-m-d, before:today)",
  "gender": "string (optional, in:male,female,other)",
  "address": "string (optional, max:500)",
  "skin_type": "string (optional, in:normal,oily,dry,combination,sensitive)",
  "skin_concerns": "array (optional)",
  "allergies": "string (optional, max:500)",
  "notes": "string (optional, max:1000)"
}
```
**Skin Concerns Options:**
```json
["acne", "aging", "pigmentation", "dull", "large_pores", "redness", "dehydration", "oily", "sensitive", "blackheads"]
```
**Response (201):**
```json
{
  "message": "Customer berhasil ditambahkan",
  "data": {
    "id": 2,
    "name": "Siti Aminah",
    "phone": "081234567891",
    ...
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Customer Detail
```
GET /customers/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Rina Wijaya",
    "phone": "081234567890",
    "email": "rina@email.com",
    "birthdate": "1990-03-15",
    "age": 35,
    "gender": "female",
    "address": "Jl. Sudirman No. 123, Jakarta",
    "skin_type": "combination",
    "skin_concerns": ["acne", "large_pores"],
    "allergies": "AHA, Parfum",
    "notes": "Customer VIP",
    "total_visits": 24,
    "total_spent": 12500000,
    "formatted_total_spent": "Rp 12.500.000",
    "last_visit": "2026-01-26",
    "created_at": "2026-01-01T10:00:00.000000Z",
    "updated_at": "2026-01-28T10:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Update Customer
```
PUT /customers/{id}
Authorization: Bearer {token}
```
**Request Body:** Same as Create Customer (all fields optional)

**Response:**
```json
{
  "message": "Customer berhasil diperbarui",
  "data": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Delete Customer
```
DELETE /customers/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "message": "Customer berhasil dihapus"
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Customer Statistics
```
GET /customers/{id}/stats
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "total_visits": 24,
    "total_spent": 12500000,
    "formatted_total_spent": "Rp 12.500.000",
    "last_visit": "2026-01-26",
    "member_since": "2024-01-15",
    "active_packages_count": 2
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Customer Treatments
```
GET /customers/{id}/treatments
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| per_page | integer | Items per page (default: 15) |
| page | integer | Page number |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "appointment_id": 10,
      "customer_id": 1,
      "staff_id": 2,
      "notes": "Kulit respond well, lanjut minggu depan",
      "products_used": ["Cleanser A", "Serum B", "Moisturizer C"],
      "before_photo": "treatments/before/abc123.jpg",
      "before_photo_url": "http://localhost/storage/treatments/before/abc123.jpg",
      "after_photo": "treatments/after/abc123.jpg",
      "after_photo_url": "http://localhost/storage/treatments/after/abc123.jpg",
      "recommendations": "Hindari sinar matahari langsung selama 24 jam",
      "follow_up_date": "2026-02-03",
      "appointment": {
        "id": 10,
        "service": {
          "id": 1,
          "name": "Facial Brightening"
        }
      },
      "staff": {
        "id": 2,
        "name": "Maya"
      },
      "created_at": "2026-01-26T14:30:00.000000Z"
    }
  ],
  "meta": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Customer Packages
```
GET /customers/{id}/packages
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| active_only | boolean | Only show usable packages |
| per_page | integer | Items per page (default: 15) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "customer_id": 1,
      "package_id": 1,
      "price_paid": 2000000,
      "formatted_price_paid": "Rp 2.000.000",
      "sessions_total": 10,
      "sessions_used": 4,
      "sessions_remaining": 6,
      "usage_percentage": 40,
      "purchased_at": "2026-01-01",
      "expires_at": "2026-04-01",
      "days_remaining": 63,
      "is_expired": false,
      "is_usable": true,
      "status": "active",
      "status_label": "Aktif",
      "package": {
        "id": 1,
        "name": "Facial Glow Package",
        "service": {
          "id": 1,
          "name": "Facial Brightening"
        }
      },
      "created_at": "2026-01-01T10:00:00.000000Z"
    }
  ],
  "meta": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Customer Appointments
```
GET /customers/{id}/appointments
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| status | string | Filter by status |
| upcoming | boolean | Only show upcoming appointments |
| per_page | integer | Items per page (default: 15) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "customer_id": 1,
      "service_id": 1,
      "staff_id": 2,
      "appointment_date": "2026-01-30",
      "start_time": "10:00",
      "end_time": "11:00",
      "status": "confirmed",
      "status_label": "Confirmed",
      "status_color": "yellow",
      "source": "whatsapp",
      "source_label": "WhatsApp",
      "notes": "Customer request extra masker",
      "service": {
        "id": 1,
        "name": "Facial Brightening"
      },
      "staff": {
        "id": 2,
        "name": "Maya"
      },
      "created_at": "2026-01-28T10:00:00.000000Z"
    }
  ],
  "meta": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

## 5. Appointments

### List Appointments
```
GET /appointments
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| status | string | Filter by status |
| start_date | date | Filter from date (Y-m-d) |
| end_date | date | Filter to date (Y-m-d) |
| staff_id | integer | Filter by staff |
| customer_id | integer | Filter by customer |
| per_page | integer | Items per page (default: 15) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "customer_id": 1,
      "service_id": 1,
      "staff_id": 2,
      "customer_package_id": null,
      "appointment_date": "2026-01-30",
      "start_time": "10:00",
      "end_time": "11:00",
      "status": "confirmed",
      "status_label": "Confirmed",
      "status_color": "yellow",
      "source": "whatsapp",
      "source_label": "WhatsApp",
      "notes": "Customer request extra masker",
      "cancelled_at": null,
      "cancelled_reason": null,
      "customer": {
        "id": 1,
        "name": "Rina Wijaya",
        "phone": "081234567890"
      },
      "service": {
        "id": 1,
        "name": "Facial Brightening",
        "duration_minutes": 60,
        "price": 250000
      },
      "staff": {
        "id": 2,
        "name": "Maya"
      },
      "created_at": "2026-01-28T10:00:00.000000Z",
      "updated_at": "2026-01-28T10:00:00.000000Z"
    }
  ],
  "meta": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Create Appointment
```
POST /appointments
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "customer_id": "integer (required)",
  "service_id": "integer (required)",
  "staff_id": "integer (optional)",
  "customer_package_id": "integer (optional)",
  "appointment_date": "date (required, format:Y-m-d, after_or_equal:today)",
  "start_time": "time (required, format:H:i)",
  "source": "string (optional, in:walk_in,phone,whatsapp,online)",
  "notes": "string (optional, max:1000)"
}
```
**Response (201):**
```json
{
  "message": "Appointment berhasil dibuat",
  "data": {
    "id": 1,
    "appointment_date": "2026-01-30",
    "start_time": "10:00",
    "end_time": "11:00",
    "status": "pending",
    ...
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Appointment Detail
```
GET /appointments/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "customer_id": 1,
    "service_id": 1,
    "staff_id": 2,
    "appointment_date": "2026-01-30",
    "start_time": "10:00",
    "end_time": "11:00",
    "status": "confirmed",
    "status_label": "Confirmed",
    "customer": {...},
    "service": {...},
    "staff": {...},
    "treatment_record": null,
    "created_at": "2026-01-28T10:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Update Appointment
```
PUT /appointments/{id}
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "customer_id": "integer (optional)",
  "service_id": "integer (optional)",
  "staff_id": "integer (optional)",
  "appointment_date": "date (optional)",
  "start_time": "time (optional)",
  "notes": "string (optional)"
}
```
**Response:**
```json
{
  "message": "Appointment berhasil diperbarui",
  "data": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Delete Appointment
```
DELETE /appointments/{id}
Authorization: Bearer {token}
```
**Note:** Cannot delete appointments with status `in_progress` or `completed`.

**Response:**
```json
{
  "message": "Appointment berhasil dihapus"
}
```
**Error Response (422):**
```json
{
  "message": "Tidak dapat menghapus appointment yang sedang berlangsung atau sudah selesai"
}
```
**Flutter Status:** ❌ Not Integrated

---

### Update Appointment Status
```
PATCH /appointments/{id}/status
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "status": "string (required, in:pending,confirmed,in_progress,completed,cancelled,no_show)",
  "cancelled_reason": "string (required if status=cancelled, max:500)"
}
```
**Available Statuses:**
| Status | Label | Color |
|--------|-------|-------|
| pending | Pending | gray |
| confirmed | Confirmed | yellow |
| in_progress | In Progress | blue |
| completed | Completed | green |
| cancelled | Cancelled | red |
| no_show | No Show | red |

**Response:**
```json
{
  "message": "Status appointment berhasil diperbarui",
  "data": {
    "id": 1,
    "status": "confirmed",
    "status_label": "Confirmed",
    ...
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Today's Appointments
```
GET /appointments-today
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| staff_id | integer | Filter by staff |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "appointment_date": "2026-01-28",
      "start_time": "09:00",
      "end_time": "10:00",
      "status": "confirmed",
      "customer": {...},
      "service": {...},
      "staff": {...}
    }
  ]
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Calendar Events
```
GET /appointments-calendar
Authorization: Bearer {token}
```
**Query Parameters (required):**
| Parameter | Type | Description |
|-----------|------|-------------|
| start_date | date | Start date (Y-m-d) |
| end_date | date | End date (Y-m-d) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Rina Wijaya - Facial Brightening",
      "start": "2026-01-28T09:00:00",
      "end": "2026-01-28T10:00:00",
      "color": "#FBBF24",
      "extendedProps": {
        "status": "confirmed",
        "customer_id": 1,
        "customer_name": "Rina Wijaya",
        "service_id": 1,
        "service_name": "Facial Brightening",
        "staff_id": 2,
        "staff_name": "Maya"
      }
    }
  ]
}
```
**Status Colors:**
- `#9CA3AF` - pending (gray)
- `#FBBF24` - confirmed (yellow)
- `#3B82F6` - in_progress (blue)
- `#10B981` - completed (green)
- `#EF4444` - cancelled/no_show (red)

**Flutter Status:** ❌ Not Integrated

---

### Get Available Slots
```
GET /appointments-available-slots
Authorization: Bearer {token}
```
**Query Parameters (required):**
| Parameter | Type | Description |
|-----------|------|-------------|
| date | date | Date to check (Y-m-d) |
| service_id | integer | Service ID |
| staff_id | integer | Staff ID (optional) |

**Response:**
```json
{
  "data": ["09:00", "09:30", "10:00", "10:30", "14:00", "14:30", "15:00"]
}
```
**If clinic is closed:**
```json
{
  "data": [],
  "message": "Klinik tutup pada hari ini"
}
```
**Flutter Status:** ❌ Not Integrated

---

## 6. Treatment Records

### List Treatment Records
```
GET /treatments
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| customer_id | integer | Filter by customer |
| with_photos | boolean | Only records with photos |
| per_page | integer | Items per page (default: 15) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "appointment_id": 10,
      "customer_id": 1,
      "staff_id": 2,
      "notes": "Kulit respond well, lanjut minggu depan",
      "products_used": ["Cleanser A", "Serum B", "Moisturizer C"],
      "before_photo": "treatments/before/abc123.jpg",
      "before_photo_url": "http://localhost/storage/treatments/before/abc123.jpg",
      "after_photo": "treatments/after/abc123.jpg",
      "after_photo_url": "http://localhost/storage/treatments/after/abc123.jpg",
      "recommendations": "Hindari sinar matahari langsung selama 24 jam",
      "follow_up_date": "2026-02-03",
      "appointment": {...},
      "customer": {...},
      "staff": {...},
      "created_at": "2026-01-26T14:30:00.000000Z"
    }
  ],
  "meta": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Create Treatment Record
```
POST /treatments
Authorization: Bearer {token}
Content-Type: multipart/form-data
```
**Request Body:**
| Field | Type | Description |
|-------|------|-------------|
| appointment_id | integer | Required, must be unique |
| customer_id | integer | Required |
| notes | string | Optional, max:2000 |
| products_used[] | array | Optional |
| before_photo | file | Optional, image, max:5MB |
| after_photo | file | Optional, image, max:5MB |
| recommendations | string | Optional, max:1000 |
| follow_up_date | date | Optional, after:today |

**Response (201):**
```json
{
  "message": "Treatment record berhasil dibuat",
  "data": {
    "id": 1,
    "appointment_id": 10,
    "before_photo_url": "http://localhost/storage/treatments/before/abc123.jpg",
    "after_photo_url": "http://localhost/storage/treatments/after/abc123.jpg",
    ...
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Treatment Record Detail
```
GET /treatments/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "appointment_id": 10,
    "customer_id": 1,
    "staff_id": 2,
    "notes": "Kulit respond well, lanjut minggu depan",
    "products_used": ["Cleanser A", "Serum B"],
    "before_photo": "treatments/before/abc123.jpg",
    "before_photo_url": "http://localhost/storage/treatments/before/abc123.jpg",
    "after_photo": "treatments/after/abc123.jpg",
    "after_photo_url": "http://localhost/storage/treatments/after/abc123.jpg",
    "recommendations": "Hindari sinar matahari langsung",
    "follow_up_date": "2026-02-03",
    "appointment": {...},
    "customer": {...},
    "staff": {...}
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Update Treatment Record
```
PUT /treatments/{id}
Authorization: Bearer {token}
Content-Type: multipart/form-data
```
**Request Body:** Same as Create (all optional)

**Response:**
```json
{
  "message": "Treatment record berhasil diperbarui",
  "data": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Delete Treatment Record
```
DELETE /treatments/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "message": "Treatment record berhasil dihapus"
}
```
**Flutter Status:** ❌ Not Integrated

---

## 7. Packages

### List Packages
```
GET /packages
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| service_id | integer | Filter by service |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Facial Glow Package",
      "description": "10x Facial Brightening dengan harga hemat",
      "service_id": 1,
      "total_sessions": 10,
      "original_price": 2500000,
      "formatted_original_price": "Rp 2.500.000",
      "package_price": 2000000,
      "formatted_package_price": "Rp 2.000.000",
      "discount_percentage": 20,
      "savings": 500000,
      "formatted_savings": "Rp 500.000",
      "price_per_session": 200000,
      "formatted_price_per_session": "Rp 200.000",
      "validity_days": 90,
      "is_active": true,
      "service": {
        "id": 1,
        "name": "Facial Brightening"
      },
      "created_at": "2026-01-01T10:00:00.000000Z"
    }
  ]
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Package Detail
```
GET /packages/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Facial Glow Package",
    "description": "10x Facial Brightening dengan harga hemat",
    "service_id": 1,
    "total_sessions": 10,
    "original_price": 2500000,
    "package_price": 2000000,
    "discount_percentage": 20,
    "validity_days": 90,
    "service": {...},
    "created_at": "2026-01-01T10:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

## 8. Customer Packages

### List Customer Packages
```
GET /customer-packages
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| customer_id | integer | Filter by customer |
| active_only | boolean | Only usable packages |
| status | string | Filter by status (active, completed, expired, cancelled) |
| per_page | integer | Items per page (default: 15) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "customer_id": 1,
      "package_id": 1,
      "sold_by": 1,
      "price_paid": 2000000,
      "formatted_price_paid": "Rp 2.000.000",
      "sessions_total": 10,
      "sessions_used": 4,
      "sessions_remaining": 6,
      "usage_percentage": 40,
      "purchased_at": "2026-01-01",
      "expires_at": "2026-04-01",
      "days_remaining": 63,
      "is_expired": false,
      "is_usable": true,
      "status": "active",
      "status_label": "Aktif",
      "notes": null,
      "customer": {...},
      "package": {...},
      "seller": {...},
      "created_at": "2026-01-01T10:00:00.000000Z"
    }
  ],
  "meta": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Customer Package Detail
```
GET /customer-packages/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "customer_id": 1,
    "package_id": 1,
    "sessions_total": 10,
    "sessions_used": 4,
    "sessions_remaining": 6,
    "is_usable": true,
    "customer": {...},
    "package": {...},
    "seller": {...}
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

## 9. Transactions

### List Transactions
```
GET /transactions
Authorization: Bearer {token}
```
**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| customer_id | integer | Filter by customer |
| status | string | Filter by status (pending, partial, paid, cancelled, refunded) |
| start_date | date | Filter from date |
| end_date | date | Filter to date |
| per_page | integer | Items per page (default: 15) |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "invoice_number": "INV202601280001",
      "customer_id": 1,
      "appointment_id": 10,
      "cashier_id": 1,
      "subtotal": 250000,
      "formatted_subtotal": "Rp 250.000",
      "discount_amount": 25000,
      "formatted_discount_amount": "Rp 25.000",
      "discount_type": "percentage",
      "tax_amount": 0,
      "total_amount": 225000,
      "formatted_total_amount": "Rp 225.000",
      "paid_amount": 225000,
      "formatted_paid_amount": "Rp 225.000",
      "change_amount": 0,
      "outstanding_amount": 0,
      "formatted_outstanding_amount": "Rp 0",
      "status": "paid",
      "status_label": "Lunas",
      "is_paid": true,
      "notes": null,
      "paid_at": "2026-01-28T15:30:00.000000Z",
      "customer": {
        "id": 1,
        "name": "Rina Wijaya"
      },
      "cashier": {
        "id": 1,
        "name": "Admin"
      },
      "created_at": "2026-01-28T15:00:00.000000Z"
    }
  ],
  "meta": {...}
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Transaction Detail
```
GET /transactions/{id}
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "id": 1,
    "invoice_number": "INV202601280001",
    "customer_id": 1,
    "subtotal": 250000,
    "discount_amount": 25000,
    "total_amount": 225000,
    "paid_amount": 225000,
    "status": "paid",
    "customer": {...},
    "appointment": {...},
    "cashier": {...},
    "items": [
      {
        "id": 1,
        "transaction_id": 1,
        "item_type": "service",
        "item_id": 1,
        "item_name": "Facial Brightening",
        "quantity": 1,
        "unit_price": 250000,
        "total_price": 250000,
        "notes": null
      }
    ],
    "payments": [
      {
        "id": 1,
        "transaction_id": 1,
        "amount": 225000,
        "payment_method": "cash",
        "payment_method_label": "Tunai",
        "reference_number": null,
        "notes": null,
        "received_by": 1,
        "paid_at": "2026-01-28T15:30:00.000000Z"
      }
    ],
    "created_at": "2026-01-28T15:00:00.000000Z"
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

### Get Receipt
```
GET /transactions/{id}/receipt
Authorization: Bearer {token}
```
**Response:**
```json
{
  "data": {
    "clinic": {
      "name": "GlowUp Clinic",
      "address": "Jl. Sudirman No. 123, Jakarta",
      "phone": "021-1234-5678"
    },
    "transaction": {
      "id": 1,
      "invoice_number": "INV202601280001",
      "customer": {
        "id": 1,
        "name": "Rina Wijaya",
        "phone": "081234567890"
      },
      "items": [...],
      "subtotal": 250000,
      "discount_amount": 25000,
      "total_amount": 225000,
      "paid_amount": 225000,
      "change_amount": 0,
      "payments": [...],
      "created_at": "2026-01-28T15:00:00.000000Z"
    }
  }
}
```
**Flutter Status:** ❌ Not Integrated

---

## 10. Integration Status

### Summary Table

| Feature | Endpoints | Flutter Status | Priority |
|---------|-----------|----------------|----------|
| Authentication | 4/4 | ❌ Not Started | High |
| Service Categories | 2/2 | ❌ Not Started | Medium |
| Services | 2/2 | ❌ Not Started | Medium |
| Customers | 8/8 | ❌ Not Started | High |
| Appointments | 8/8 | ❌ Not Started | High |
| Treatment Records | 5/5 | ❌ Not Started | High |
| Packages | 2/2 | ❌ Not Started | Medium |
| Customer Packages | 2/2 | ❌ Not Started | Medium |
| Transactions | 3/3 | ❌ Not Started | Medium |

**Total:** 0/36 endpoints integrated (0%)

---

### Endpoint Priority List

#### High Priority (Core Features)
1. **POST /login** - Login
2. **POST /logout** - Logout
3. **GET /profile** - Get profile
4. **GET /customers** - List customers
5. **POST /customers** - Create customer
6. **GET /customers/{id}** - Get customer detail
7. **GET /appointments** - List appointments
8. **POST /appointments** - Create appointment
9. **PATCH /appointments/{id}/status** - Update status
10. **GET /appointments-today** - Today's appointments
11. **GET /appointments-available-slots** - Available slots
12. **GET /treatments** - List treatments
13. **POST /treatments** - Create treatment (with photo upload)

#### Medium Priority
1. **GET /services** - List services
2. **GET /service-categories** - List categories
3. **GET /packages** - List packages
4. **GET /customer-packages** - Customer packages
5. **GET /transactions** - List transactions

#### Low Priority
1. **PUT /profile** - Update profile
2. **PUT /customers/{id}** - Update customer
3. **DELETE /customers/{id}** - Delete customer
4. **PUT /appointments/{id}** - Update appointment
5. **DELETE /appointments/{id}** - Delete appointment
6. **PUT /treatments/{id}** - Update treatment
7. **DELETE /treatments/{id}** - Delete treatment

---

## 11. Flutter Implementation Guide

### File Structure

```
lib/
├── core/
│   ├── constants/
│   │   └── api_constants.dart
│   └── network/
│       └── api_client.dart
├── data/
│   ├── datasources/
│   │   ├── auth_remote_datasource.dart
│   │   ├── customer_remote_datasource.dart
│   │   ├── service_remote_datasource.dart
│   │   ├── appointment_remote_datasource.dart
│   │   ├── treatment_remote_datasource.dart
│   │   ├── package_remote_datasource.dart
│   │   └── transaction_remote_datasource.dart
│   └── models/
│       ├── user_model.dart
│       ├── customer_model.dart
│       ├── service_model.dart
│       ├── service_category_model.dart
│       ├── appointment_model.dart
│       ├── treatment_model.dart
│       ├── package_model.dart
│       ├── customer_package_model.dart
│       └── transaction_model.dart
└── presentation/
    └── bloc/
        ├── auth/
        ├── customer/
        ├── appointment/
        └── ...
```

---

### API Constants

```dart
// lib/core/constants/api_constants.dart
class ApiConstants {
  static const String baseUrl = 'https://your-domain.com/api/v1';

  // Auth
  static const String login = '/login';
  static const String logout = '/logout';
  static const String profile = '/profile';

  // Services
  static const String services = '/services';
  static const String serviceCategories = '/service-categories';

  // Customers
  static const String customers = '/customers';

  // Appointments
  static const String appointments = '/appointments';
  static const String appointmentsToday = '/appointments-today';
  static const String appointmentsCalendar = '/appointments-calendar';
  static const String appointmentsAvailableSlots = '/appointments-available-slots';

  // Treatments
  static const String treatments = '/treatments';

  // Packages
  static const String packages = '/packages';
  static const String customerPackages = '/customer-packages';

  // Transactions
  static const String transactions = '/transactions';
}
```

---

### Auth Datasource Example

```dart
// lib/data/datasources/auth_remote_datasource.dart
import 'dart:convert';
import 'package:dartz/dartz.dart';
import 'package:http/http.dart' as http;
import '../models/user_model.dart';
import '../../core/constants/api_constants.dart';

class AuthRemoteDatasource {
  Future<Either<String, Map<String, dynamic>>> login({
    required String email,
    required String password,
    String? deviceName,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('${ApiConstants.baseUrl}${ApiConstants.login}'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'email': email,
          'password': password,
          'device_name': deviceName ?? 'flutter-app',
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200) {
        return Right({
          'user': UserModel.fromJson(data['data']['user']),
          'token': data['data']['token'],
        });
      } else {
        return Left(data['message'] ?? 'Login failed');
      }
    } catch (e) {
      return Left(e.toString());
    }
  }

  Future<Either<String, bool>> logout(String token) async {
    try {
      final response = await http.post(
        Uri.parse('${ApiConstants.baseUrl}${ApiConstants.logout}'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        return const Right(true);
      } else {
        final data = jsonDecode(response.body);
        return Left(data['message'] ?? 'Logout failed');
      }
    } catch (e) {
      return Left(e.toString());
    }
  }

  Future<Either<String, UserModel>> getProfile(String token) async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConstants.baseUrl}${ApiConstants.profile}'),
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return Right(UserModel.fromJson(data['data']));
      } else {
        return const Left('Failed to get profile');
      }
    } catch (e) {
      return Left(e.toString());
    }
  }
}
```

---

### User Model Example

```dart
// lib/data/models/user_model.dart
class UserModel {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String role;
  final String roleLabel;
  final String? avatar;
  final bool isActive;
  final DateTime? createdAt;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    required this.role,
    required this.roleLabel,
    this.avatar,
    required this.isActive,
    this.createdAt,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      role: json['role'],
      roleLabel: json['role_label'],
      avatar: json['avatar'],
      isActive: json['is_active'] ?? true,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'role': role,
      'role_label': roleLabel,
      'avatar': avatar,
      'is_active': isActive,
      'created_at': createdAt?.toIso8601String(),
    };
  }
}
```

---

### Customer Model Example

```dart
// lib/data/models/customer_model.dart
class CustomerModel {
  final int id;
  final String name;
  final String phone;
  final String? email;
  final String? birthdate;
  final int? age;
  final String? gender;
  final String? address;
  final String? skinType;
  final List<String>? skinConcerns;
  final String? allergies;
  final String? notes;
  final int totalVisits;
  final double totalSpent;
  final String formattedTotalSpent;
  final String? lastVisit;
  final DateTime? createdAt;

  CustomerModel({
    required this.id,
    required this.name,
    required this.phone,
    this.email,
    this.birthdate,
    this.age,
    this.gender,
    this.address,
    this.skinType,
    this.skinConcerns,
    this.allergies,
    this.notes,
    required this.totalVisits,
    required this.totalSpent,
    required this.formattedTotalSpent,
    this.lastVisit,
    this.createdAt,
  });

  factory CustomerModel.fromJson(Map<String, dynamic> json) {
    return CustomerModel(
      id: json['id'],
      name: json['name'],
      phone: json['phone'],
      email: json['email'],
      birthdate: json['birthdate'],
      age: json['age'],
      gender: json['gender'],
      address: json['address'],
      skinType: json['skin_type'],
      skinConcerns: json['skin_concerns'] != null
          ? List<String>.from(json['skin_concerns'])
          : null,
      allergies: json['allergies'],
      notes: json['notes'],
      totalVisits: json['total_visits'] ?? 0,
      totalSpent: (json['total_spent'] ?? 0).toDouble(),
      formattedTotalSpent: json['formatted_total_spent'] ?? 'Rp 0',
      lastVisit: json['last_visit'],
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
    );
  }
}
```

---

### Appointment Model Example

```dart
// lib/data/models/appointment_model.dart
class AppointmentModel {
  final int id;
  final int customerId;
  final int serviceId;
  final int? staffId;
  final int? customerPackageId;
  final String appointmentDate;
  final String startTime;
  final String endTime;
  final String status;
  final String statusLabel;
  final String statusColor;
  final String source;
  final String sourceLabel;
  final String? notes;
  final DateTime? cancelledAt;
  final String? cancelledReason;
  final CustomerModel? customer;
  final ServiceModel? service;
  final UserModel? staff;
  final DateTime? createdAt;

  AppointmentModel({
    required this.id,
    required this.customerId,
    required this.serviceId,
    this.staffId,
    this.customerPackageId,
    required this.appointmentDate,
    required this.startTime,
    required this.endTime,
    required this.status,
    required this.statusLabel,
    required this.statusColor,
    required this.source,
    required this.sourceLabel,
    this.notes,
    this.cancelledAt,
    this.cancelledReason,
    this.customer,
    this.service,
    this.staff,
    this.createdAt,
  });

  factory AppointmentModel.fromJson(Map<String, dynamic> json) {
    return AppointmentModel(
      id: json['id'],
      customerId: json['customer_id'],
      serviceId: json['service_id'],
      staffId: json['staff_id'],
      customerPackageId: json['customer_package_id'],
      appointmentDate: json['appointment_date'],
      startTime: json['start_time'],
      endTime: json['end_time'],
      status: json['status'],
      statusLabel: json['status_label'],
      statusColor: json['status_color'],
      source: json['source'],
      sourceLabel: json['source_label'],
      notes: json['notes'],
      cancelledAt: json['cancelled_at'] != null
          ? DateTime.parse(json['cancelled_at'])
          : null,
      cancelledReason: json['cancelled_reason'],
      customer: json['customer'] != null
          ? CustomerModel.fromJson(json['customer'])
          : null,
      service: json['service'] != null
          ? ServiceModel.fromJson(json['service'])
          : null,
      staff: json['staff'] != null
          ? UserModel.fromJson(json['staff'])
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
    );
  }
}
```

---

## Error Handling

### Standard Error Response
```json
{
  "message": "Error description"
}
```

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Not Found (404)
```json
{
  "message": "No query results for model [App\\Models\\Customer] 999"
}
```

---

## Changelog

### 2026-01-28 (v1.0.0)
- Initial API release
- Authentication (login, logout, profile, update profile)
- Service Categories (list, detail)
- Services (list, detail)
- Customers (CRUD + stats, treatments, packages, appointments)
- Appointments (CRUD + status update, today, calendar, available slots)
- Treatment Records (CRUD with photo upload)
- Packages (list, detail)
- Customer Packages (list, detail)
- Transactions (list, detail, receipt)

---

**Generated by Claude Code**
