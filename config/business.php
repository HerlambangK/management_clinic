<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Business Types Configuration
    |--------------------------------------------------------------------------
    |
    | Define all supported business types with their specific configurations
    | including labels, colors, profile fields, and sample data.
    |
    */

    'types' => [
        'clinic' => [
            'name' => 'Klinik Kecantikan',
            'name_en' => 'Beauty Clinic',
            'description' => 'Klinik perawatan kulit dan kecantikan',
            'description_en' => 'Skin care and beauty clinic',
            'icon' => 'sparkles',
            'staff_label' => 'Beautician',
            'staff_label_plural' => 'Beauticians',
            'service_label' => 'Treatment',
            'service_label_plural' => 'Treatments',
            'profile_section' => 'Profil Kulit',
            'profile_section_en' => 'Skin Profile',

            // Features enabled for this business type
            'features' => [
                'treatment_records' => true,      // Rekam medis/treatment dengan foto before/after
                'skin_analysis' => true,          // Analisis kulit pelanggan
                'packages' => false,              // Paket treatment (non-gym disabled)
                'customer_packages' => false,     // Penjualan paket ke pelanggan (non-gym disabled)
                'products' => true,               // Inventory produk
                'loyalty' => true,                // Program loyalty/membership
                'online_booking' => true,         // Booking online dari landing page
                'customer_portal' => true,        // Portal pelanggan
                'walk_in_queue' => false,         // Antrian walk-in (tidak untuk klinik)
            ],

            // Theme colors (Tailwind classes)
            'theme' => [
                'primary' => 'pink',
                'primary_hex' => '#ec4899',
                'gradient_from' => 'from-pink-500',
                'gradient_to' => 'to-rose-500',
                'bg_light' => 'bg-pink-50',
                'bg_medium' => 'bg-pink-100',
                'text_primary' => 'text-pink-600',
                'text_dark' => 'text-pink-700',
                'border' => 'border-pink-200',
                'ring' => 'ring-pink-500',
                'button' => 'bg-pink-600 hover:bg-pink-700',
                'button_outline' => 'border-pink-600 text-pink-600 hover:bg-pink-50',
            ],

            // Customer profile fields
            'profile_fields' => [
                'type' => [
                    'label' => 'Tipe Kulit',
                    'label_en' => 'Skin Type',
                    'options' => [
                        'normal' => ['id' => 'Normal', 'en' => 'Normal'],
                        'oily' => ['id' => 'Berminyak', 'en' => 'Oily'],
                        'dry' => ['id' => 'Kering', 'en' => 'Dry'],
                        'combination' => ['id' => 'Kombinasi', 'en' => 'Combination'],
                        'sensitive' => ['id' => 'Sensitif', 'en' => 'Sensitive'],
                    ],
                ],
                'concerns' => [
                    'label' => 'Masalah Kulit',
                    'label_en' => 'Skin Concerns',
                    'multiple' => true,
                    'options' => [
                        'acne' => ['id' => 'Jerawat', 'en' => 'Acne'],
                        'aging' => ['id' => 'Anti-Aging', 'en' => 'Anti-Aging'],
                        'pigmentation' => ['id' => 'Pigmentasi', 'en' => 'Pigmentation'],
                        'dull' => ['id' => 'Kusam', 'en' => 'Dull Skin'],
                        'large_pores' => ['id' => 'Pori-pori Besar', 'en' => 'Large Pores'],
                        'redness' => ['id' => 'Kemerahan', 'en' => 'Redness'],
                        'dehydration' => ['id' => 'Dehidrasi', 'en' => 'Dehydration'],
                        'blackheads' => ['id' => 'Komedo', 'en' => 'Blackheads'],
                    ],
                ],
            ],

            // Sample categories for seeding
            'sample_categories' => [
                ['name' => 'Facial Treatment', 'icon' => 'face-smile'],
                ['name' => 'Peeling & Exfoliation', 'icon' => 'sparkles'],
                ['name' => 'Anti-Aging', 'icon' => 'clock'],
                ['name' => 'Acne Treatment', 'icon' => 'shield-check'],
                ['name' => 'Brightening', 'icon' => 'sun'],
            ],

            // Sample services
            'sample_services' => [
                'Facial Treatment' => [
                    ['name' => 'Facial Brightening', 'price' => 250000, 'duration' => 60],
                    ['name' => 'Facial Deep Cleansing', 'price' => 200000, 'duration' => 60],
                    ['name' => 'Facial Hydrating', 'price' => 275000, 'duration' => 60],
                ],
                'Peeling & Exfoliation' => [
                    ['name' => 'Chemical Peeling', 'price' => 350000, 'duration' => 45],
                    ['name' => 'Microdermabrasion', 'price' => 400000, 'duration' => 60],
                ],
                'Anti-Aging' => [
                    ['name' => 'Anti-Aging Facial', 'price' => 400000, 'duration' => 75],
                    ['name' => 'Botox Treatment', 'price' => 1500000, 'duration' => 30],
                ],
                'Acne Treatment' => [
                    ['name' => 'Acne Facial', 'price' => 350000, 'duration' => 90],
                    ['name' => 'Acne Extraction', 'price' => 250000, 'duration' => 60],
                ],
                'Brightening' => [
                    ['name' => 'Whitening Treatment', 'price' => 450000, 'duration' => 75],
                    ['name' => 'Laser Toning', 'price' => 600000, 'duration' => 45],
                ],
            ],
        ],

        'salon' => [
            'name' => 'Salon',
            'name_en' => 'Hair Salon',
            'description' => 'Salon perawatan dan styling rambut',
            'description_en' => 'Hair care and styling salon',
            'icon' => 'scissors',
            'staff_label' => 'Hairstylist',
            'staff_label_plural' => 'Hairstylists',
            'service_label' => 'Layanan',
            'service_label_plural' => 'Layanan',
            'profile_section' => 'Profil Rambut',
            'profile_section_en' => 'Hair Profile',

            // Features enabled for this business type
            'features' => [
                'treatment_records' => false,     // Salon tidak perlu rekam medis
                'skin_analysis' => false,         // Tidak ada analisis kulit
                'packages' => false,              // Paket layanan (non-gym disabled)
                'customer_packages' => false,     // Penjualan paket ke pelanggan (non-gym disabled)
                'products' => true,               // Inventory produk (shampoo, conditioner, dll)
                'loyalty' => true,                // Program loyalty/membership
                'online_booking' => true,         // Booking online
                'customer_portal' => true,        // Portal pelanggan
                'walk_in_queue' => true,          // Antrian walk-in
            ],

            // Theme colors
            'theme' => [
                'primary' => 'purple',
                'primary_hex' => '#9333ea',
                'gradient_from' => 'from-purple-500',
                'gradient_to' => 'to-violet-500',
                'bg_light' => 'bg-purple-50',
                'bg_medium' => 'bg-purple-100',
                'text_primary' => 'text-purple-600',
                'text_dark' => 'text-purple-700',
                'border' => 'border-purple-200',
                'ring' => 'ring-purple-500',
                'button' => 'bg-purple-600 hover:bg-purple-700',
                'button_outline' => 'border-purple-600 text-purple-600 hover:bg-purple-50',
            ],

            // Customer profile fields
            'profile_fields' => [
                'type' => [
                    'label' => 'Tipe Rambut',
                    'label_en' => 'Hair Type',
                    'options' => [
                        'normal' => ['id' => 'Normal', 'en' => 'Normal'],
                        'oily' => ['id' => 'Berminyak', 'en' => 'Oily'],
                        'dry' => ['id' => 'Kering', 'en' => 'Dry'],
                        'damaged' => ['id' => 'Rusak', 'en' => 'Damaged'],
                        'color_treated' => ['id' => 'Diwarnai', 'en' => 'Color Treated'],
                        'curly' => ['id' => 'Keriting', 'en' => 'Curly'],
                        'straight' => ['id' => 'Lurus', 'en' => 'Straight'],
                        'wavy' => ['id' => 'Bergelombang', 'en' => 'Wavy'],
                    ],
                ],
                'concerns' => [
                    'label' => 'Masalah Rambut',
                    'label_en' => 'Hair Concerns',
                    'multiple' => true,
                    'options' => [
                        'dandruff' => ['id' => 'Ketombe', 'en' => 'Dandruff'],
                        'hair_loss' => ['id' => 'Rambut Rontok', 'en' => 'Hair Loss'],
                        'split_ends' => ['id' => 'Bercabang', 'en' => 'Split Ends'],
                        'dull' => ['id' => 'Kusam', 'en' => 'Dull'],
                        'frizzy' => ['id' => 'Mengembang', 'en' => 'Frizzy'],
                        'oily_scalp' => ['id' => 'Kulit Kepala Berminyak', 'en' => 'Oily Scalp'],
                        'dry_scalp' => ['id' => 'Kulit Kepala Kering', 'en' => 'Dry Scalp'],
                        'thinning' => ['id' => 'Menipis', 'en' => 'Thinning'],
                    ],
                ],
            ],

            // Sample categories
            'sample_categories' => [
                ['name' => 'Potong Rambut', 'icon' => 'scissors'],
                ['name' => 'Coloring & Highlight', 'icon' => 'paint-brush'],
                ['name' => 'Treatment Rambut', 'icon' => 'sparkles'],
                ['name' => 'Styling', 'icon' => 'star'],
                ['name' => 'Creambath & Spa', 'icon' => 'hand-raised'],
            ],

            // Sample services
            'sample_services' => [
                'Potong Rambut' => [
                    ['name' => 'Potong Rambut Wanita', 'price' => 150000, 'duration' => 60],
                    ['name' => 'Potong Rambut Pria', 'price' => 75000, 'duration' => 30],
                    ['name' => 'Potong Rambut Anak', 'price' => 50000, 'duration' => 30],
                    ['name' => 'Potong Poni', 'price' => 35000, 'duration' => 15],
                ],
                'Coloring & Highlight' => [
                    ['name' => 'Full Color', 'price' => 350000, 'duration' => 120],
                    ['name' => 'Highlight', 'price' => 400000, 'duration' => 120],
                    ['name' => 'Balayage', 'price' => 500000, 'duration' => 150],
                    ['name' => 'Root Touch Up', 'price' => 200000, 'duration' => 60],
                ],
                'Treatment Rambut' => [
                    ['name' => 'Hair Mask', 'price' => 150000, 'duration' => 45],
                    ['name' => 'Keratin Treatment', 'price' => 500000, 'duration' => 120],
                    ['name' => 'Hair Botox', 'price' => 600000, 'duration' => 120],
                    ['name' => 'Scalp Treatment', 'price' => 200000, 'duration' => 45],
                ],
                'Styling' => [
                    ['name' => 'Blow Dry', 'price' => 100000, 'duration' => 45],
                    ['name' => 'Curling', 'price' => 150000, 'duration' => 60],
                    ['name' => 'Hair Up / Sanggul', 'price' => 300000, 'duration' => 90],
                ],
                'Creambath & Spa' => [
                    ['name' => 'Creambath', 'price' => 100000, 'duration' => 60],
                    ['name' => 'Hair Spa', 'price' => 175000, 'duration' => 75],
                    ['name' => 'Head Massage', 'price' => 75000, 'duration' => 30],
                ],
            ],
        ],

        'barbershop' => [
            'name' => 'Barbershop',
            'name_en' => 'Barbershop',
            'description' => 'Barbershop untuk pria modern',
            'description_en' => 'Modern barbershop for men',
            'icon' => 'scissors',
            'staff_label' => 'Barber',
            'staff_label_plural' => 'Barbers',
            'service_label' => 'Layanan',
            'service_label_plural' => 'Layanan',
            'profile_section' => 'Profil Pelanggan',
            'profile_section_en' => 'Customer Profile',

            // Features enabled for this business type
            'features' => [
                'treatment_records' => false,     // Barbershop tidak perlu rekam medis
                'skin_analysis' => false,         // Tidak ada analisis kulit
                'packages' => false,              // Barbershop biasanya tidak pakai paket
                'customer_packages' => false,     // Tidak ada paket pelanggan
                'products' => true,               // Inventory produk (pomade, wax, dll)
                'loyalty' => true,                // Program loyalty (stamp card, dll)
                'online_booking' => true,         // Booking online
                'customer_portal' => false,       // Barbershop biasanya walk-in
                'walk_in_queue' => true,          // Antrian walk-in (fitur utama barbershop)
            ],

            // Theme colors
            'theme' => [
                'primary' => 'blue',
                'primary_hex' => '#3b82f6',
                'gradient_from' => 'from-blue-500',
                'gradient_to' => 'to-blue-600',
                'bg_light' => 'bg-blue-50',
                'bg_medium' => 'bg-blue-100',
                'text_primary' => 'text-blue-600',
                'text_dark' => 'text-blue-700',
                'border' => 'border-blue-200',
                'ring' => 'ring-blue-500',
                'button' => 'bg-blue-500 hover:bg-blue-600',
                'button_outline' => 'border-blue-500 text-blue-500 hover:bg-blue-50',
            ],

            // Customer profile fields
            'profile_fields' => [
                'type' => [
                    'label' => 'Tipe Rambut',
                    'label_en' => 'Hair Type',
                    'options' => [
                        'normal' => ['id' => 'Normal', 'en' => 'Normal'],
                        'oily' => ['id' => 'Berminyak', 'en' => 'Oily'],
                        'dry' => ['id' => 'Kering', 'en' => 'Dry'],
                        'thick' => ['id' => 'Tebal', 'en' => 'Thick'],
                        'thin' => ['id' => 'Tipis', 'en' => 'Thin'],
                        'curly' => ['id' => 'Keriting', 'en' => 'Curly'],
                        'straight' => ['id' => 'Lurus', 'en' => 'Straight'],
                    ],
                ],
                'concerns' => [
                    'label' => 'Preferensi & Masalah',
                    'label_en' => 'Preferences & Concerns',
                    'multiple' => true,
                    'options' => [
                        'dandruff' => ['id' => 'Ketombe', 'en' => 'Dandruff'],
                        'hair_loss' => ['id' => 'Rambut Rontok', 'en' => 'Hair Loss'],
                        'oily_scalp' => ['id' => 'Kulit Kepala Berminyak', 'en' => 'Oily Scalp'],
                        'beard_care' => ['id' => 'Perawatan Jenggot', 'en' => 'Beard Care'],
                        'sensitive_skin' => ['id' => 'Kulit Sensitif', 'en' => 'Sensitive Skin'],
                        'receding_hairline' => ['id' => 'Garis Rambut Mundur', 'en' => 'Receding Hairline'],
                    ],
                ],
            ],

            // Sample categories
            'sample_categories' => [
                ['name' => 'Potong Rambut', 'icon' => 'scissors'],
                ['name' => 'Cukur & Shaving', 'icon' => 'identification'],
                ['name' => 'Treatment', 'icon' => 'sparkles'],
                ['name' => 'Styling', 'icon' => 'star'],
                ['name' => 'Paket Combo', 'icon' => 'gift'],
            ],

            // Sample services
            'sample_services' => [
                'Potong Rambut' => [
                    ['name' => 'Potong Rambut Reguler', 'price' => 50000, 'duration' => 30],
                    ['name' => 'Potong Rambut Premium', 'price' => 75000, 'duration' => 45],
                    ['name' => 'Potong Rambut + Cuci', 'price' => 65000, 'duration' => 45],
                    ['name' => 'Kids Haircut', 'price' => 40000, 'duration' => 20],
                ],
                'Cukur & Shaving' => [
                    ['name' => 'Cukur Jenggot', 'price' => 25000, 'duration' => 15],
                    ['name' => 'Cukur Kumis', 'price' => 15000, 'duration' => 10],
                    ['name' => 'Hot Towel Shave', 'price' => 50000, 'duration' => 30],
                    ['name' => 'Beard Trim & Shape', 'price' => 35000, 'duration' => 20],
                ],
                'Treatment' => [
                    ['name' => 'Hair Tonic', 'price' => 30000, 'duration' => 15],
                    ['name' => 'Creambath Pria', 'price' => 75000, 'duration' => 45],
                    ['name' => 'Scalp Treatment', 'price' => 100000, 'duration' => 45],
                    ['name' => 'Hair Coloring', 'price' => 150000, 'duration' => 60],
                ],
                'Styling' => [
                    ['name' => 'Pomade Styling', 'price' => 25000, 'duration' => 15],
                    ['name' => 'Hair Wax', 'price' => 20000, 'duration' => 10],
                    ['name' => 'Gel Styling', 'price' => 15000, 'duration' => 10],
                ],
                'Paket Combo' => [
                    ['name' => 'Paket Lengkap (Potong + Cukur + Cuci)', 'price' => 85000, 'duration' => 60],
                    ['name' => 'Paket Premium (Potong + Shave + Treatment)', 'price' => 125000, 'duration' => 75],
                    ['name' => 'Paket Groom (Potong + Jenggot + Styling)', 'price' => 100000, 'duration' => 60],
                ],
            ],
        ],
        'gym' => [
            'name' => 'Gym Membership',
            'name_en' => 'Gym Membership',
            'description' => 'Keanggotaan gym dengan presensi QR',
            'description_en' => 'Gym membership with QR attendance',
            'icon' => 'dumbbell',
            'staff_label' => 'Trainer',
            'staff_label_plural' => 'Trainers',
            'service_label' => 'Program',
            'service_label_plural' => 'Programs',
            'profile_section' => 'Profil Member',
            'profile_section_en' => 'Member Profile',

            // Features enabled for this business type
            'features' => [
                'treatment_records' => false,
                'skin_analysis' => false,
                'packages' => true,               // Membership packages
                'customer_packages' => true,      // Membership sales to members
                'products' => true,               // Gym retail products
                'loyalty' => true,                // Points/rewards
                'online_booking' => true,         // Class booking if needed
                'customer_portal' => true,        // Member portal
                'walk_in_queue' => false,
                'attendance' => true,             // QR attendance
            ],

            // Theme colors
            'theme' => [
                'primary' => 'emerald',
                'primary_hex' => '#10b981',
                'gradient_from' => 'from-emerald-500',
                'gradient_to' => 'to-teal-500',
                'bg_light' => 'bg-emerald-50',
                'bg_medium' => 'bg-emerald-100',
                'text_primary' => 'text-emerald-600',
                'text_dark' => 'text-emerald-700',
                'border' => 'border-emerald-200',
                'ring' => 'ring-emerald-500',
                'button' => 'bg-emerald-600 hover:bg-emerald-700',
                'button_outline' => 'border-emerald-600 text-emerald-600 hover:bg-emerald-50',
            ],

            // Member profile fields
            'profile_fields' => [
                'type' => [
                    'label' => 'Level Kebugaran',
                    'label_en' => 'Fitness Level',
                    'options' => [
                        'beginner' => ['id' => 'Pemula', 'en' => 'Beginner'],
                        'intermediate' => ['id' => 'Menengah', 'en' => 'Intermediate'],
                        'advanced' => ['id' => 'Lanjutan', 'en' => 'Advanced'],
                    ],
                ],
                'concerns' => [
                    'label' => 'Tujuan Latihan',
                    'label_en' => 'Training Goals',
                    'multiple' => true,
                    'options' => [
                        'weight_loss' => ['id' => 'Turun Berat Badan', 'en' => 'Weight Loss'],
                        'muscle_gain' => ['id' => 'Menaikkan Massa Otot', 'en' => 'Muscle Gain'],
                        'endurance' => ['id' => 'Daya Tahan', 'en' => 'Endurance'],
                        'flexibility' => ['id' => 'Fleksibilitas', 'en' => 'Flexibility'],
                        'rehab' => ['id' => 'Pemulihan', 'en' => 'Rehab'],
                    ],
                ],
            ],

            // Sample categories
            'sample_categories' => [
                ['name' => 'Membership', 'icon' => 'badge-check'],
                ['name' => 'Personal Training', 'icon' => 'user-group'],
                ['name' => 'Classes', 'icon' => 'calendar'],
            ],

            // Sample services
            'sample_services' => [
                'Membership' => [
                    ['name' => 'Monthly Membership', 'price' => 300000, 'duration' => 30],
                    ['name' => 'Quarterly Membership', 'price' => 800000, 'duration' => 30],
                ],
                'Personal Training' => [
                    ['name' => 'PT Session 60 Minutes', 'price' => 150000, 'duration' => 60],
                    ['name' => 'PT Session 90 Minutes', 'price' => 220000, 'duration' => 90],
                ],
                'Classes' => [
                    ['name' => 'HIIT Class', 'price' => 75000, 'duration' => 60],
                    ['name' => 'Yoga Class', 'price' => 80000, 'duration' => 60],
                    ['name' => 'Strength Training', 'price' => 90000, 'duration' => 60],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Business Type
    |--------------------------------------------------------------------------
    */
    'default' => 'clinic',
];
