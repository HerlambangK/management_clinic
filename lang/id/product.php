<?php

return [
    'title' => 'Produk',
    'subtitle' => 'Kelola inventaris produk Anda',
    'category_title' => 'Kategori Produk',
    'category_subtitle' => 'Kelola kategori produk',

    // Fields
    'name' => 'Nama Produk',
    'sku' => 'SKU',
    'category' => 'Kategori',
    'description' => 'Deskripsi',
    'price' => 'Harga Jual',
    'cost_price' => 'Harga Modal',
    'stock' => 'Stok',
    'min_stock' => 'Stok Minimum',
    'unit' => 'Satuan',
    'image' => 'Gambar',
    'track_stock' => 'Lacak Stok',

    // Units
    'unit_pcs' => 'pcs',
    'unit_box' => 'box',
    'unit_bottle' => 'botol',
    'unit_tube' => 'tube',
    'unit_pack' => 'pack',
    'unit_set' => 'set',

    // Placeholders
    'search_placeholder' => 'Cari nama atau SKU...',
    'select_category' => 'Pilih kategori',
    'all_categories' => 'Semua Kategori',

    // Status
    'all_stock' => 'Semua Stok',
    'low_stock' => 'Stok Menipis',
    'out_of_stock' => 'Stok Habis',
    'in_stock' => 'Tersedia',

    // Messages
    'created' => 'Produk berhasil ditambahkan.',
    'updated' => 'Produk berhasil diperbarui.',
    'deleted' => 'Produk berhasil dihapus.',
    'activated' => 'Produk telah diaktifkan.',
    'deactivated' => 'Produk telah dinonaktifkan.',
    'has_transactions' => 'Produk tidak dapat dihapus karena sudah memiliki transaksi.',
    'sku_exists' => 'SKU ini sudah digunakan.',
    'stock_adjusted' => 'Stok berhasil disesuaikan.',
    'stock_insufficient' => 'Stok tidak mencukupi.',

    // Category Messages
    'category_created' => 'Kategori produk berhasil ditambahkan.',
    'category_updated' => 'Kategori produk berhasil diperbarui.',
    'category_deleted' => 'Kategori produk berhasil dihapus.',
    'category_has_products' => 'Kategori tidak dapat dihapus karena masih memiliki produk.',
    'category_reordered' => 'Urutan kategori berhasil diperbarui.',

    // Actions
    'add_product' => 'Tambah Produk',
    'edit_product' => 'Edit Produk',
    'add_category' => 'Tambah Kategori',
    'edit_category' => 'Edit Kategori',
    'adjust_stock' => 'Sesuaikan Stok',
    'view_categories' => 'Lihat Kategori',

    // Labels
    'no_products' => 'Belum ada produk.',
    'no_categories' => 'Belum ada kategori.',
    'product_count' => ':count produk',
    'low_stock_alert' => ':count produk dengan stok menipis',

    // Stock Adjustment
    'stock_adjustment' => 'Penyesuaian Stok',
    'adjustment_amount' => 'Jumlah Penyesuaian',
    'adjustment_reason' => 'Alasan',
    'adjustment_hint' => 'Gunakan angka positif untuk menambah, negatif untuk mengurangi',
];
