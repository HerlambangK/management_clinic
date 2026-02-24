# GlowUp Design System

**Tailwind CSS 4 + Alpine.js**

---

## Color Palette

Warna yang menggambarkan **GlowUp** - elegan, profesional, dan fresh untuk klinik kecantikan.

### Primary Colors (Rose - Soft & Elegant)

```css
/* Tailwind Config */
rose: {
    50:  '#fff1f2',   /* Background sangat terang */
    100: '#ffe4e6',   /* Background hover */
    200: '#fecdd3',   /* Border, divider */
    300: '#fda4af',   /* Icon background */
    400: '#fb7185',   /* Accent hover */
    500: '#f43f5e',   /* Primary action */
    600: '#e11d48',   /* Primary button */
    700: '#be123c',   /* Primary hover */
    800: '#9f1239',   /* Dark accent */
    900: '#881337',   /* Dark text */
}
```

### Secondary/Accent Colors

```css
/* Coral-ish untuk warmth */
primary: {
    50:  '#fdf4f3',
    100: '#fce8e6',
    200: '#f9d5d2',
    300: '#f4b5ae',
    400: '#ec8b81',
    500: '#e06456',   /* Secondary action */
    600: '#cc4637',   /* Button */
    700: '#ab372a',   /* Hover */
    800: '#8e3127',
    900: '#772e26',
}
```

### Neutral Colors

```css
gray: {
    50:  '#f9fafb',   /* Page background */
    100: '#f3f4f6',   /* Card background alt */
    200: '#e5e7eb',   /* Border */
    300: '#d1d5db',   /* Disabled */
    400: '#9ca3af',   /* Placeholder */
    500: '#6b7280',   /* Secondary text */
    600: '#4b5563',   /* Body text */
    700: '#374151',   /* Headings */
    800: '#1f2937',   /* Dark headings */
    900: '#111827',   /* Black text */
}
```

### Semantic Colors

```css
/* Success - Treatment selesai, pembayaran sukses */
emerald: {
    50:  '#ecfdf5',
    100: '#d1fae5',
    500: '#10b981',
    600: '#059669',
}

/* Warning - Pending, perlu perhatian */
amber: {
    50:  '#fffbeb',
    100: '#fef3c7',
    500: '#f59e0b',
    600: '#d97706',
}

/* Info - In progress, informasi */
blue: {
    50:  '#eff6ff',
    100: '#dbeafe',
    500: '#3b82f6',
    600: '#2563eb',
}

/* Violet - Premium, packages */
violet: {
    50:  '#f5f3ff',
    100: '#ede9fe',
    500: '#8b5cf6',
    600: '#7c3aed',
}
```

### Background Tones

```css
cream: '#FFF9F5',    /* Main page background */
peach: '#FFEEE8',    /* Section highlight */
```

---

## Gradients (Subtle - Tidak Lebay)

### Primary Gradient
```css
/* Button, header accent - rose to coral */
bg-gradient-to-r from-rose-500 to-primary-600

/* Lebih soft untuk background besar */
bg-gradient-to-br from-rose-100/40 to-primary-100/30
```

### Card Hover Gradient
```css
/* Subtle shine effect */
bg-gradient-to-br from-white to-rose-50/50
```

### Status Gradients (Icon backgrounds)
```css
/* Success */
bg-gradient-to-br from-emerald-400 to-emerald-600

/* Warning */
bg-gradient-to-br from-amber-400 to-amber-600

/* Info */
bg-gradient-to-br from-blue-400 to-blue-600
```

---

## Typography

### Font Families

```css
/* Display/Headings - Playfair Display */
font-display: ['Playfair Display', 'serif']

/* Body/UI - DM Sans */
font-sans: ['DM Sans', 'sans-serif']
```

### Font Sizes

| Element | Class | Size |
|---------|-------|------|
| Page Title | `text-2xl font-bold` | 24px |
| Section Title | `text-lg font-semibold` | 18px |
| Card Title | `text-base font-medium` | 16px |
| Body | `text-sm` | 14px |
| Caption | `text-xs` | 12px |

### Usage

```html
<!-- Page Title -->
<h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>

<!-- Section Title -->
<h3 class="text-lg font-semibold text-gray-900">Today's Appointments</h3>

<!-- Card Title -->
<p class="text-base font-medium text-gray-900">Rina Wijaya</p>

<!-- Body Text -->
<p class="text-sm text-gray-600">Facial Brightening Treatment</p>

<!-- Caption/Label -->
<span class="text-xs text-gray-500">Last visit: 2 days ago</span>
```

---

## Components

### Buttons

```html
<!-- Primary Button -->
<button class="px-4 py-2 bg-gradient-to-r from-rose-500 to-primary-600 text-white text-sm font-medium rounded-xl hover:shadow-lg hover:shadow-rose-200 transition-all">
    Save
</button>

<!-- Secondary Button -->
<button class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
    Cancel
</button>

<!-- Ghost Button -->
<button class="px-4 py-2 text-rose-600 text-sm font-medium rounded-xl hover:bg-rose-50 transition-colors">
    View All
</button>

<!-- Danger Button -->
<button class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-xl hover:bg-red-600 transition-colors">
    Delete
</button>
```

### Cards

```html
<!-- Stats Card -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
            <!-- Icon -->
        </div>
        <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">+12%</span>
    </div>
    <p class="text-sm text-gray-500 mb-1">Revenue Today</p>
    <p class="text-2xl font-bold text-gray-900">Rp 3.2 Jt</p>
</div>

<!-- List Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Section Title</h3>
    </div>
    <div class="p-6">
        <!-- Content -->
    </div>
</div>
```

### Form Inputs

```html
<!-- Text Input -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
    <input type="text"
           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent"
           placeholder="Enter name">
</div>

<!-- Select -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
    <select class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
        <option>Select category</option>
    </select>
</div>

<!-- Textarea -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
    <textarea rows="4"
              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent resize-none"
              placeholder="Enter notes"></textarea>
</div>
```

### Status Badges

```html
<!-- Completed -->
<span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Completed</span>

<!-- In Progress -->
<span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">In Progress</span>

<!-- Confirmed -->
<span class="px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">Confirmed</span>

<!-- Pending -->
<span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">Pending</span>

<!-- Cancelled -->
<span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Cancelled</span>
```

### Navigation Active State

```html
<!-- Active -->
<a class="flex items-center px-3 py-2.5 rounded-xl bg-rose-50 text-rose-600">
    <!-- Icon -->
    <span class="ml-3 font-medium">Dashboard</span>
</a>

<!-- Inactive -->
<a class="flex items-center px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50">
    <!-- Icon -->
    <span class="ml-3 font-medium">Customers</span>
</a>
```

---

## Spacing & Sizing

### Border Radius

| Element | Class |
|---------|-------|
| Button | `rounded-xl` (12px) |
| Card | `rounded-2xl` (16px) |
| Modal | `rounded-3xl` (24px) |
| Avatar | `rounded-lg` (8px) |
| Badge | `rounded-full` |
| Input | `rounded-xl` (12px) |

### Shadows

```css
/* Card default */
shadow-sm border border-gray-100

/* Card hover */
shadow-lg shadow-rose-100/50

/* Button hover */
hover:shadow-lg hover:shadow-rose-200

/* Dropdown */
shadow-lg border border-gray-100
```

### Spacing Scale

| Purpose | Size |
|---------|------|
| Card padding | `p-6` (24px) |
| Section gap | `gap-6` (24px) |
| List item gap | `space-y-4` (16px) |
| Form field gap | `space-y-6` (24px) |
| Button padding | `px-4 py-2` |

---

## Alpine.js Patterns

### Toggle Sidebar

```html
<div x-data="{ sidebarOpen: true }">
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'">
        <!-- Sidebar content -->
    </aside>
    <button @click="sidebarOpen = !sidebarOpen">Toggle</button>
</div>
```

### Dropdown

```html
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open">Menu</button>
    <div x-show="open"
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg">
        <!-- Menu items -->
    </div>
</div>
```

### Modal

```html
<div x-data="{ showModal: false }">
    <button @click="showModal = true">Open Modal</button>

    <div x-show="showModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div @click.away="showModal = false"
             class="bg-white rounded-3xl p-6 max-w-lg w-full mx-4">
            <!-- Modal content -->
        </div>
    </div>
</div>
```

### Tab Navigation

```html
<div x-data="{ activeTab: 'overview' }">
    <div class="flex space-x-4 border-b">
        <button @click="activeTab = 'overview'"
                :class="activeTab === 'overview' ? 'border-rose-500 text-rose-600' : 'border-transparent text-gray-500'"
                class="pb-2 border-b-2 font-medium">
            Overview
        </button>
        <button @click="activeTab = 'history'"
                :class="activeTab === 'history' ? 'border-rose-500 text-rose-600' : 'border-transparent text-gray-500'"
                class="pb-2 border-b-2 font-medium">
            History
        </button>
    </div>

    <div x-show="activeTab === 'overview'">Overview content</div>
    <div x-show="activeTab === 'history'">History content</div>
</div>
```

---

## Tailwind Config

```javascript
// tailwind.config.js
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#fdf4f3',
                    100: '#fce8e6',
                    200: '#f9d5d2',
                    300: '#f4b5ae',
                    400: '#ec8b81',
                    500: '#e06456',
                    600: '#cc4637',
                    700: '#ab372a',
                    800: '#8e3127',
                    900: '#772e26',
                },
                cream: '#FFF9F5',
                peach: '#FFEEE8',
            },
            fontFamily: {
                display: ['Playfair Display', 'serif'],
                sans: ['DM Sans', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
```
