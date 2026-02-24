<?php

return [
    // Page Titles
    'title' => 'Loyalty Program',
    'subtitle' => 'Kelola poin dan reward pelanggan',
    'customers_title' => 'Pelanggan Loyalty',
    'customers_subtitle' => 'Lihat pelanggan berdasarkan poin loyalty',
    'redemptions_title' => 'Redemption',
    'redemptions_subtitle' => 'Kelola penukaran reward',
    'rewards_title' => 'Kelola Reward',
    'rewards_subtitle' => 'Buat dan kelola reward yang tersedia',
    'customer_history_title' => 'Riwayat Poin :name',

    // Navigation
    'customers' => 'Pelanggan',
    'redemptions' => 'Redemption',
    'manage_rewards' => 'Kelola Reward',

    // Stats
    'total_earned' => 'Total Poin Didapat',
    'total_redeemed' => 'Total Poin Ditukar',
    'active_customers' => 'Pelanggan Aktif',
    'pending_redemptions' => 'Menunggu Digunakan',
    'used_redemptions' => 'Sudah Digunakan',
    'total_redemptions' => 'Total Redemption',
    'total_rewards' => 'Total Reward',
    'active_rewards' => 'Reward Aktif',

    // Table Headers
    'date' => 'Tanggal',
    'customer' => 'Pelanggan',
    'type' => 'Tipe',
    'points' => 'Poin',
    'balance' => 'Saldo',
    'description' => 'Deskripsi',
    'tier' => 'Tier',
    'current_points' => 'Poin Saat Ini',
    'lifetime_points' => 'Poin Seumur Hidup',
    'code' => 'Kode',
    'reward' => 'Reward',
    'points_used' => 'Poin Digunakan',
    'valid_until' => 'Berlaku Sampai',
    'points_required' => 'Poin Dibutuhkan',
    'value' => 'Nilai',
    'stock' => 'Stok',

    // Filters
    'search_customer' => 'Cari nama atau telepon pelanggan...',
    'search_code_or_customer' => 'Cari kode atau nama pelanggan...',
    'search_reward' => 'Cari reward...',
    'all_types' => 'Semua Tipe',
    'all_tiers' => 'Semua Tier',
    'all_status' => 'Semua Status',

    // Customer History
    'points_history' => 'Riwayat Poin',
    'available_rewards' => 'Reward Tersedia',
    'active_redemptions' => 'Redemption Aktif',
    'member_since' => 'Member sejak :date',
    'adjust_points' => 'Sesuaikan Poin',
    'points_amount' => 'Jumlah poin (positif/negatif)',
    'adjust_points_help' => 'Gunakan nilai positif untuk menambah, negatif untuk mengurangi',
    'reason' => 'Alasan',
    'adjust' => 'Sesuaikan',
    'lifetime' => 'Seumur Hidup',

    // Redemption
    'enter_code' => 'Masukkan kode redemption...',
    'use_code' => 'Gunakan Kode',
    'use' => 'Gunakan',
    'redeem' => 'Tukar',
    'confirm_redeem' => 'Apakah Anda yakin ingin menukar reward ini?',
    'confirm_cancel' => 'Apakah Anda yakin ingin membatalkan?',

    // Rewards Form
    'add_reward' => 'Tambah Reward',
    'edit_reward' => 'Edit Reward',
    'reward_name' => 'Nama Reward',
    'reward_name_placeholder' => 'Contoh: Diskon 10%',
    'description_placeholder' => 'Deskripsi reward...',
    'reward_type' => 'Tipe Reward',
    'select_type' => 'Pilih tipe reward',
    'reward_value' => 'Nilai Reward',
    'discount_percent_help' => 'Masukkan persentase diskon (contoh: 10 untuk 10%)',
    'discount_amount_help' => 'Masukkan nilai diskon dalam Rupiah',
    'select_service' => 'Pilih Layanan',
    'select_product' => 'Pilih Produk',
    'unlimited' => 'Tidak terbatas',
    'stock_help' => 'Kosongkan untuk stok tidak terbatas',
    'max_per_customer' => 'Maks per Pelanggan',
    'max_per_customer_help' => 'Kosongkan untuk tidak ada batas',
    'valid_from' => 'Berlaku Dari',

    // Messages - Success
    'reward_created' => 'Reward berhasil ditambahkan.',
    'reward_updated' => 'Reward berhasil diperbarui.',
    'reward_deleted' => 'Reward berhasil dihapus.',
    'reward_activated' => 'Reward telah diaktifkan.',
    'reward_deactivated' => 'Reward telah dinonaktifkan.',
    'points_adjusted' => 'Poin berhasil disesuaikan.',
    'redeem_success' => 'Berhasil menukar reward :reward. Kode: :code',
    'code_used_success' => 'Reward :reward untuk :customer berhasil digunakan.',
    'redemption_cancelled' => 'Redemption berhasil dibatalkan.',

    // Messages - Error
    'cannot_redeem' => 'Tidak dapat menukar reward ini.',
    'insufficient_points' => 'Poin tidak mencukupi.',
    'code_not_found' => 'Kode tidak ditemukan.',
    'code_already_used' => 'Kode sudah digunakan.',
    'code_expired' => 'Kode sudah kadaluarsa.',
    'code_cancelled' => 'Kode sudah dibatalkan.',
    'code_invalid' => 'Kode tidak valid.',
    'code_valid' => 'Kode valid dan siap digunakan.',
    'cannot_cancel_redemption' => 'Redemption tidak dapat dibatalkan.',
    'reward_has_redemptions' => 'Reward tidak dapat dihapus karena sudah memiliki redemption.',
    'valid_until_after_from' => 'Tanggal berakhir harus setelah tanggal mulai.',

    // Point Description Templates
    'points_from_transaction' => 'Poin dari transaksi :invoice',
    'redeemed_for' => 'Ditukar untuk :reward',
    'points_refunded' => 'Pengembalian poin untuk :reward',

    // Empty States
    'no_history' => 'Belum ada riwayat poin',
    'no_customers' => 'Belum ada data pelanggan',
    'no_redemptions' => 'Belum ada redemption',
    'no_rewards' => 'Belum ada reward',
    'no_available_rewards' => 'Tidak ada reward yang tersedia',
    'no_active_redemptions' => 'Tidak ada redemption aktif',

    // Customer Show Page
    'loyalty_points' => 'Loyalty Points',
    'earn_info' => 'Dapatkan 1 poin setiap Rp :points belanja',

    // Referrals
    'referrals' => 'Referral',
    'referrals_title' => 'Program Referral',
    'referrals_subtitle' => 'Lihat semua referral pelanggan',
    'referrer' => 'Pengundang',
    'referee' => 'Yang Diundang',
    'referrer_points' => 'Poin Pengundang',
    'referee_points' => 'Poin Diundang',
    'total_referrals' => 'Total Referral',
    'pending_referrals' => 'Menunggu Transaksi',
    'rewarded_referrals' => 'Sudah Diberi Reward',
    'total_points_given' => 'Total Poin Diberikan',
    'search_referral' => 'Cari nama atau telepon...',
    'no_referrals' => 'Belum ada data referral',
    'referral_bonus_referrer' => 'Bonus referral - mengajak :name',
    'referral_bonus_referee' => 'Bonus referral - diajak oleh :name',
    'your_referral_code' => 'Kode Referral Anda',
    'copy_code' => 'Salin Kode',
    'share_code' => 'Bagikan Kode',
    'referral_stats' => 'Statistik Referral',
    'successful_referrals' => 'Berhasil Mengajak',
    'total_bonus_earned' => 'Total Bonus Didapat',

    // Customer referral section
    'referral_code' => 'Kode Referral',
    'share_referral_code' => 'Bagikan kode ini untuk mendapatkan bonus poin',
    'total_bonus_points' => 'Total Bonus Poin',
    'pending' => 'Menunggu',
    'rewarded' => 'Diberi Reward',
    'code_copied' => 'Kode berhasil disalin!',

    // Points redemption in transaction
    'use_points' => 'Gunakan Poin',
    'available' => 'Tersedia',
    'points' => 'poin',
    'use_max' => 'Pakai Maksimal',
    'points_discount' => 'Diskon Poin',
    'points_used_transaction' => 'Digunakan untuk transaksi :invoice',
    'points_refunded_transaction' => 'Pengembalian poin dari transaksi :invoice',
];
