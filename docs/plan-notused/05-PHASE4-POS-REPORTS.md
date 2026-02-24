# Phase 4: POS / Checkout & Reports

**Priority:** P1 (Important)
**Estimasi:** Week 3-4

---

## Module A: Transactions (POS)

### A.1 Database

- [ ] **Create transactions migration**
  ```bash
  php artisan make:migration create_transactions_table
  ```
  Fields:
  - id
  - invoice_number: varchar(50) unique
  - customer_id: foreign key
  - appointment_id: foreign key nullable
  - subtotal: decimal(14,2)
  - discount_type: enum('percentage', 'fixed') nullable
  - discount_value: decimal(12,2) nullable default 0
  - discount_amount: decimal(14,2) default 0
  - tax_amount: decimal(14,2) default 0
  - total: decimal(14,2)
  - payment_method: enum('cash', 'qris', 'transfer', 'card', 'other')
  - payment_status: enum('pending', 'paid', 'partial', 'refunded')
  - amount_paid: decimal(14,2) nullable
  - change_amount: decimal(14,2) nullable
  - paid_at: timestamp nullable
  - notes: text nullable
  - created_by: foreign key (user)
  - timestamps

- [ ] **Create transaction_items migration**
  ```bash
  php artisan make:migration create_transaction_items_table
  ```
  Fields:
  - id
  - transaction_id: foreign key
  - item_type: enum('service', 'product', 'package')
  - item_id: bigint
  - item_name: varchar(255) (snapshot nama)
  - quantity: int default 1
  - unit_price: decimal(12,2)
  - subtotal: decimal(12,2)
  - timestamps

### A.2 Models

- [ ] **Create Transaction Model**
  ```bash
  php artisan make:model Transaction -f
  ```

  ```php
  protected $fillable = [
      'invoice_number', 'customer_id', 'appointment_id',
      'subtotal', 'discount_type', 'discount_value', 'discount_amount',
      'tax_amount', 'total', 'payment_method', 'payment_status',
      'amount_paid', 'change_amount', 'paid_at', 'notes', 'created_by'
  ];

  protected function casts(): array
  {
      return [
          'subtotal' => 'decimal:2',
          'discount_value' => 'decimal:2',
          'discount_amount' => 'decimal:2',
          'tax_amount' => 'decimal:2',
          'total' => 'decimal:2',
          'amount_paid' => 'decimal:2',
          'change_amount' => 'decimal:2',
          'paid_at' => 'datetime',
      ];
  }

  // Generate invoice number
  public static function generateInvoiceNumber(): string
  {
      $prefix = setting('invoice_prefix', 'INV');
      $date = now()->format('Ymd');
      $sequence = self::whereDate('created_at', today())->count() + 1;

      return sprintf('%s-%s-%03d', $prefix, $date, $sequence);
  }

  // Relationships
  public function customer(): BelongsTo
  {
      return $this->belongsTo(Customer::class);
  }

  public function items(): HasMany
  {
      return $this->hasMany(TransactionItem::class);
  }

  public function createdBy(): BelongsTo
  {
      return $this->belongsTo(User::class, 'created_by');
  }
  ```

- [ ] **Create TransactionItem Model**
  ```bash
  php artisan make:model TransactionItem
  ```

### A.3 Service Layer

- [ ] **Create TransactionService**
  ```bash
  php artisan make:class Services/TransactionService
  ```

  Methods:
  - createFromAppointment(Appointment): Transaction
  - addItem(Transaction, type, itemId, quantity): TransactionItem
  - removeItem(TransactionItem): bool
  - applyDiscount(Transaction, type, value): Transaction
  - calculateTotals(Transaction): Transaction
  - processPayment(Transaction, method, amountPaid): Transaction
  - generateReceipt(Transaction): array|string

### A.4 Controller

- [ ] **Create TransactionController**
  ```bash
  php artisan make:controller TransactionController --resource
  ```

  Methods:
  - index() - List all transactions
  - create(?appointment_id) - Checkout form
  - store(Request) - Create transaction & process payment
  - show(Transaction) - Detail/receipt view
  - void(Transaction) - Void transaction (owner only)

### A.5 Views

- [ ] **Checkout Page** (resources/views/transactions/create.blade.php)

  ```
  ┌─────────────────────────────────────────────────────────────┐
  │ Checkout                                                    │
  ├─────────────────────────────────────────────────────────────┤
  │ Customer: Rina Wijaya                                       │
  │ Appointment: 26 Jan 2026, 10:00                            │
  ├─────────────────────────────────────────────────────────────┤
  │                                                             │
  │ ITEMS                                                       │
  │ ┌─────────────────────────────────────────────────────────┐│
  │ │ Item                    Qty   Price        Subtotal     ││
  │ │─────────────────────────────────────────────────────────││
  │ │ Facial Brightening      1     Rp 250.000   Rp 250.000   ││
  │ │                                            [Remove]     ││
  │ │─────────────────────────────────────────────────────────││
  │ │ [+ Add Service]  [+ Add Product]  [+ Redeem Package]    ││
  │ └─────────────────────────────────────────────────────────┘│
  │                                                             │
  │ DISCOUNT                                                    │
  │ ┌─────────────────────────────────────────────────────────┐│
  │ │ ○ Percentage  ● Fixed Amount                            ││
  │ │ Value: [25000   ]                       = Rp 25.000     ││
  │ │ Reason: [Member discount           ]                    ││
  │ └─────────────────────────────────────────────────────────┘│
  │                                                             │
  │ SUMMARY                                                     │
  │ ┌─────────────────────────────────────────────────────────┐│
  │ │ Subtotal                              Rp 250.000        ││
  │ │ Discount                             -Rp  25.000        ││
  │ │ ───────────────────────────────────────────────         ││
  │ │ TOTAL                                 Rp 225.000        ││
  │ └─────────────────────────────────────────────────────────┘│
  │                                                             │
  │ PAYMENT METHOD                                              │
  │ ┌─────────────────────────────────────────────────────────┐│
  │ │ ● Cash  ○ QRIS  ○ Transfer  ○ Card                     ││
  │ │                                                         ││
  │ │ Amount Received: [Rp 300.000    ]                       ││
  │ │ Change:          Rp 75.000                              ││
  │ └─────────────────────────────────────────────────────────┘│
  │                                                             │
  │                      [Cancel]  [Complete & Print Receipt]   │
  └─────────────────────────────────────────────────────────────┘
  ```

  Alpine.js features:
  - Dynamic item list (add/remove)
  - Auto-calculate subtotals
  - Discount toggle (percentage/fixed)
  - Auto-calculate change for cash
  - Payment method selection
  - Validation before submit

- [ ] **Transactions List** (resources/views/transactions/index.blade.php)
  - Date filter (today, this week, custom range)
  - Payment status filter
  - Table: Invoice#, Customer, Total, Payment Method, Status, Date, Actions
  - Export to CSV/Excel (nice to have)

- [ ] **Receipt View** (resources/views/transactions/receipt.blade.php)
  - Print-friendly layout
  - Clinic header (name, address, phone)
  - Invoice number & date
  - Customer name
  - Items table
  - Subtotal, discount, total
  - Payment info
  - Footer message

  ```
  ┌─────────────────────────────────────────┐
  │         GLOW AESTHETIC CLINIC           │
  │    Jl. Sudirman No. 123, Jakarta        │
  │          Tel: 021-1234-5678             │
  ├─────────────────────────────────────────┤
  │ Invoice: INV-20260126-001               │
  │ Date: 26 Jan 2026 10:45                 │
  │ Cashier: Admin                          │
  │ Customer: Rina Wijaya                   │
  ├─────────────────────────────────────────┤
  │ Item                        Subtotal    │
  │─────────────────────────────────────────│
  │ Facial Brightening                      │
  │ 1 x Rp 250.000             Rp 250.000   │
  ├─────────────────────────────────────────┤
  │ Subtotal                   Rp 250.000   │
  │ Discount                  -Rp  25.000   │
  │─────────────────────────────────────────│
  │ TOTAL                      Rp 225.000   │
  ├─────────────────────────────────────────┤
  │ Payment: Cash              Rp 300.000   │
  │ Change                     Rp  75.000   │
  ├─────────────────────────────────────────┤
  │                                         │
  │     Terima kasih atas kunjungan         │
  │          Anda. Sampai jumpa!            │
  │                                         │
  └─────────────────────────────────────────┘
  ```

---

## Module B: Reports

### B.1 Service Layer

- [ ] **Create ReportService**
  ```bash
  php artisan make:class Services/ReportService
  ```

  Methods:
  - getRevenueReport(startDate, endDate): array
  - getDailyRevenue(date): float
  - getRevenueByPaymentMethod(startDate, endDate): array
  - getRevenueByServiceCategory(startDate, endDate): array
  - getPopularServices(limit, startDate, endDate): Collection
  - getCustomerReport(startDate, endDate): array
  - getNewCustomers(startDate, endDate): int
  - getReturningCustomers(startDate, endDate): int
  - getTopSpenders(limit, startDate, endDate): Collection
  - getInactiveCustomers(days): Collection
  - getAppointmentReport(startDate, endDate): array
  - getDashboardStats(date): array

### B.2 Controller

- [ ] **Create ReportController**
  ```bash
  php artisan make:controller ReportController
  ```

  Methods:
  - revenue() - Revenue report page
  - services() - Service popularity report
  - customers() - Customer analytics
  - export(type, format) - Export to PDF/Excel

### B.3 Views

- [ ] **Revenue Report** (resources/views/reports/revenue.blade.php)

  ```
  ┌─────────────────────────────────────────────────────────────┐
  │ Revenue Report                            [Export PDF/Excel]│
  ├─────────────────────────────────────────────────────────────┤
  │ Period: [This Month ▼]  From: [01/01/2026] To: [31/01/2026]│
  ├─────────────────────────────────────────────────────────────┤
  │                                                             │
  │ SUMMARY                                                     │
  │ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐            │
  │ │ Total       │ │ Transactions│ │ Average     │            │
  │ │ Revenue     │ │ Count       │ │ per Trans   │            │
  │ │ Rp 47.5 Jt  │ │ 156         │ │ Rp 304.487  │            │
  │ │ +23% ▲      │ │ +12% ▲      │ │ +8% ▲       │            │
  │ └─────────────┘ └─────────────┘ └─────────────┘            │
  │                                                             │
  │ REVENUE TREND                                               │
  │ ┌─────────────────────────────────────────────────────────┐│
  │ │ [Line Chart - Daily Revenue]                            ││
  │ └─────────────────────────────────────────────────────────┘│
  │                                                             │
  │ BY PAYMENT METHOD              BY SERVICE CATEGORY          │
  │ ┌───────────────────┐        ┌───────────────────────┐     │
  │ │ [Pie/Donut Chart] │        │ [Horizontal Bar]      │     │
  │ │                   │        │                       │     │
  │ │ Cash      Rp 20Jt │        │ Facial      Rp 25Jt   │     │
  │ │ QRIS      Rp 15Jt │        │ Body        Rp 12Jt   │     │
  │ │ Transfer  Rp 10Jt │        │ Laser       Rp  8Jt   │     │
  │ │ Card      Rp  2Jt │        │ Other       Rp  2Jt   │     │
  │ └───────────────────┘        └───────────────────────┘     │
  │                                                             │
  │ DAILY BREAKDOWN                                             │
  │ ┌─────────────────────────────────────────────────────────┐│
  │ │ Date     │ Transactions │ Revenue      │ vs Prev Day   ││
  │ │──────────┼──────────────┼──────────────┼───────────────││
  │ │ 26 Jan   │ 8            │ Rp 2.450.000 │ +15% ▲        ││
  │ │ 25 Jan   │ 6            │ Rp 2.130.000 │ -8% ▼         ││
  │ │ 24 Jan   │ 7            │ Rp 2.320.000 │ +5% ▲         ││
  │ └─────────────────────────────────────────────────────────┘│
  └─────────────────────────────────────────────────────────────┘
  ```

- [ ] **Services Report** (resources/views/reports/services.blade.php)
  - Most booked services (bar chart)
  - Revenue per service
  - Trend over time
  - Table: Service, Bookings, Revenue, Avg Price

- [ ] **Customer Report** (resources/views/reports/customers.blade.php)
  - New vs returning customers (line chart)
  - Customer growth trend
  - Top spenders table
  - Inactive customers (needs follow up)

### B.4 Dashboard Integration

Update Dashboard dengan real data:

```php
// DashboardController
public function index()
{
    $reportService = app(ReportService::class);
    $today = today();

    return view('dashboard.index', [
        'revenueToday' => $reportService->getDailyRevenue($today),
        'appointmentsToday' => Appointment::whereDate('appointment_date', $today)->count(),
        'newCustomersWeek' => $reportService->getNewCustomers(
            $today->startOfWeek(),
            $today->endOfWeek()
        ),
        'completedToday' => Appointment::whereDate('appointment_date', $today)
            ->where('status', 'completed')
            ->count(),
        'revenueChart' => $reportService->getMonthlyRevenue(12),
        'popularServices' => $reportService->getPopularServices(5),
        'todayAppointments' => Appointment::with(['customer', 'service', 'staff'])
            ->whereDate('appointment_date', $today)
            ->orderBy('start_time')
            ->get(),
    ]);
}
```

---

## Charts (Chart.js)

### Setup
```javascript
// resources/js/charts.js
import Chart from 'chart.js/auto';

// Make available globally for Alpine
window.Chart = Chart;
```

### Revenue Bar Chart
```javascript
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', ...],
        datasets: [{
            label: 'Revenue (Juta Rp)',
            data: [32, 28, 35, ...],
            backgroundColor: 'rgba(244, 63, 94, 0.8)', // rose-500
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
```

### Payment Method Donut
```javascript
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Cash', 'QRIS', 'Transfer', 'Card'],
        datasets: [{
            data: [40, 30, 25, 5],
            backgroundColor: [
                '#f43f5e', // rose-500
                '#8b5cf6', // violet-500
                '#3b82f6', // blue-500
                '#10b981', // emerald-500
            ]
        }]
    },
    options: {
        responsive: true,
        cutout: '60%',
    }
});
```

---

## Testing Checklist

### Transactions
- [ ] Create transaction dari appointment
- [ ] Add multiple items ke transaction
- [ ] Remove item dari transaction
- [ ] Apply percentage discount
- [ ] Apply fixed amount discount
- [ ] Calculate totals correctly
- [ ] Process cash payment dengan kembalian
- [ ] Process QRIS payment
- [ ] Generate invoice number unique per hari
- [ ] Print/view receipt
- [ ] List transactions dengan filter
- [ ] Customer total_spent updated setelah payment

### Reports
- [ ] Revenue report dengan date range
- [ ] Revenue chart menampilkan data benar
- [ ] Breakdown by payment method
- [ ] Breakdown by service category
- [ ] Services report - popular services
- [ ] Customer report - new vs returning
- [ ] Top spenders list
- [ ] Dashboard stats real-time

---

## Files Summary

```
app/
├── Http/Controllers/
│   ├── TransactionController.php
│   └── ReportController.php
├── Http/Requests/
│   └── StoreTransactionRequest.php
├── Models/
│   ├── Transaction.php
│   └── TransactionItem.php
└── Services/
    ├── TransactionService.php
    └── ReportService.php

database/
├── migrations/
│   ├── xxxx_create_transactions_table.php
│   └── xxxx_create_transaction_items_table.php
└── factories/
    └── TransactionFactory.php

resources/
├── views/
│   ├── transactions/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── show.blade.php
│   │   └── receipt.blade.php
│   └── reports/
│       ├── revenue.blade.php
│       ├── services.blade.php
│       └── customers.blade.php
└── js/
    └── charts.js
```
