# GlowUp - Design System

## Color Palette

### Primary Colors (Rose Theme)
```css
/* Defined in resources/css/app.css */
--color-rose-50: #fff1f2
--color-rose-100: #ffe4e6
--color-rose-200: #fecdd3
--color-rose-300: #fda4af
--color-rose-400: #fb7185
--color-rose-500: #f43f5e (Primary)
--color-rose-600: #e11d48
--color-rose-700: #be123c
--color-rose-800: #9f1239
--color-rose-900: #881337
```

### Secondary Colors (Coral/Primary)
```css
--color-primary-50: #fdf4f3
--color-primary-100: #fce8e6
--color-primary-200: #f9d2ce
--color-primary-300: #f4b0a8
--color-primary-400: #ec8578
--color-primary-500: #e06456
--color-primary-600: #cc4637 (Secondary)
--color-primary-700: #ab3729
--color-primary-800: #8e3126
--color-primary-900: #772e25
```

### Neutral Colors
```css
--color-cream: #FFF9F5 (Background)
--color-peach: #FFEEE8 (Accent Background)
```

### Semantic Colors
```css
/* Success */
--color-green-500: #22c55e

/* Warning */
--color-amber-500: #f59e0b

/* Error */
--color-red-500: #ef4444

/* Info */
--color-blue-500: #3b82f6
```

---

## Typography

### Font Families
```css
--font-sans: 'DM Sans', ui-sans-serif, system-ui, sans-serif
--font-display: 'Playfair Display', ui-serif, Georgia, serif
```

### Usage
- **Headings (h1, h2, h3):** font-display (Playfair Display)
- **Body text:** font-sans (DM Sans)
- **UI elements (buttons, labels):** font-sans (DM Sans)

### Font Sizes
```css
text-xs: 0.75rem (12px)
text-sm: 0.875rem (14px)
text-base: 1rem (16px)
text-lg: 1.125rem (18px)
text-xl: 1.25rem (20px)
text-2xl: 1.5rem (24px)
text-3xl: 1.875rem (30px)
text-4xl: 2.25rem (36px)
text-5xl: 3rem (48px)
text-6xl: 3.75rem (60px)
```

---

## Component Patterns

### Buttons

#### Primary Button
```html
<button class="px-6 py-3 bg-gradient-to-r from-rose-500 to-primary-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-rose-200 transition-all">
    Button Text
</button>
```

#### Secondary Button
```html
<button class="px-6 py-3 border-2 border-rose-200 text-gray-700 font-semibold rounded-xl hover:border-rose-400 hover:bg-rose-50 transition-all">
    Button Text
</button>
```

#### Ghost Button
```html
<button class="px-4 py-2 text-gray-600 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
    Button Text
</button>
```

### Cards

#### Basic Card
```html
<div class="bg-white rounded-2xl p-6 border border-gray-100 hover:border-rose-200 hover:shadow-lg transition-all">
    <!-- Content -->
</div>
```

#### Stats Card
```html
<div class="bg-white rounded-2xl p-6 border border-gray-100">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">Label</p>
            <p class="text-2xl font-bold text-gray-900">Value</p>
        </div>
        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
            <!-- Icon -->
        </div>
    </div>
</div>
```

### Form Elements

#### Input Field
```html
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
    <input type="text" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition-all" placeholder="Placeholder">
</div>
```

#### Select
```html
<select class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-rose-500 focus:ring-2 focus:ring-rose-200 transition-all">
    <option>Option 1</option>
</select>
```

### Badges

#### Status Badges
```html
<!-- Pending -->
<span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">Pending</span>

<!-- Confirmed -->
<span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">Confirmed</span>

<!-- In Progress -->
<span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-medium rounded-full">In Progress</span>

<!-- Completed -->
<span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Completed</span>

<!-- Cancelled -->
<span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">Cancelled</span>
```

---

## Layout Patterns

### Dashboard Layout
```
┌─────────────────────────────────────────────────────────────┐
│ SIDEBAR (w-64)   │            MAIN CONTENT                  │
│                  │  ┌─────────────────────────────────────┐ │
│ Logo             │  │ HEADER (sticky top)                 │ │
│ Navigation       │  │ Search | Actions | Profile          │ │
│ - Dashboard      │  └─────────────────────────────────────┘ │
│ - Appointments   │                                          │
│ - Customers      │  ┌─────────────────────────────────────┐ │
│ - Services       │  │ PAGE CONTENT                        │ │
│ - Packages       │  │                                     │ │
│ - Transactions   │  │                                     │ │
│ - Reports        │  │                                     │ │
│ ─────────────    │  │                                     │ │
│ - Settings       │  │                                     │ │
│                  │  └─────────────────────────────────────┘ │
└──────────────────┴──────────────────────────────────────────┘
```

### Responsive Breakpoints
```css
sm: 640px
md: 768px
lg: 1024px
xl: 1280px
2xl: 1536px
```

---

## Icons

Menggunakan Heroicons (outline style)
- Install: Included via SVG inline atau Blade components
- Docs: https://heroicons.com

### Common Icons
```html
<!-- Dashboard -->
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
</svg>

<!-- Calendar -->
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
</svg>

<!-- Users -->
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
</svg>
```

---

## Animation & Transitions

### Standard Transitions
```css
transition-all: all 150ms cubic-bezier(0.4, 0, 0.2, 1)
transition-colors: color, background-color, border-color 150ms
```

### Custom Animations (defined in app.css)
```css
/* Float animation for decorative elements */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

/* Fade in up for page elements */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pulse soft for badges */
@keyframes pulse-soft {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
```

---

## Spacing System

Menggunakan Tailwind default spacing scale:
```
0: 0px
1: 0.25rem (4px)
2: 0.5rem (8px)
3: 0.75rem (12px)
4: 1rem (16px)
5: 1.25rem (20px)
6: 1.5rem (24px)
8: 2rem (32px)
10: 2.5rem (40px)
12: 3rem (48px)
16: 4rem (64px)
20: 5rem (80px)
24: 6rem (96px)
```

### Standard Spacing Usage
- **Card padding:** p-6 atau p-8
- **Section padding:** py-16 atau py-24
- **Gap between items:** gap-4 atau gap-6
- **Form field spacing:** space-y-4
