<?php

return [
    'title' => 'Import Data',
    'subtitle' => 'Import data dari file Excel untuk migrasi data ke GlowUp',

    // Entity types
    'customers' => 'Pelanggan',
    'services' => 'Layanan',
    'packages' => 'Paket Treatment',

    // Entity descriptions
    'customers_desc' => 'Import data pelanggan dari file Excel',
    'services_desc' => 'Import data layanan dan kategori',
    'packages_desc' => 'Import data paket treatment',

    // Upload
    'upload_title' => 'Unggah File Excel',
    'select_file' => 'Pilih File',
    'click_to_upload' => 'Klik untuk upload',
    'drag_drop' => 'atau drag & drop',
    'file_format' => 'Excel (.xlsx, .xls) max. 10MB',
    'upload_preview' => 'Unggah & Preview',
    'download_template' => 'Download Template Excel',

    // Instructions
    'instructions' => 'Petunjuk Import',
    'file_format_title' => 'Format File',
    'format_excel' => 'File harus berformat Excel (.xlsx atau .xls)',
    'format_header' => 'Baris pertama harus berisi nama kolom',
    'format_size' => 'Ukuran maksimal 10MB',
    'format_sheet' => 'Data harus ada di sheet pertama',
    'required_columns' => 'Kolom Wajib',
    'available_columns' => 'Kolom Tersedia',
    'update_warning' => 'Data dengan nomor telepon/nama yang sudah ada akan diperbarui, bukan diduplikasi.',

    // Preview
    'preview_title' => 'Preview Data',
    'preview_subtitle' => 'Periksa data sebelum import',
    'data_preview' => 'Preview Data',
    'total_rows' => 'Total :count baris data akan diimport',
    'validation_ok' => 'Validasi berhasil',
    'validation_error' => 'Ada masalah dengan data',
    'back_to_upload' => 'Kembali ke Upload',
    'process_import' => 'Proses Import',

    // History
    'history' => 'Riwayat Import',
    'date' => 'Tanggal',
    'data_type' => 'Jenis Data',
    'file' => 'File',
    'result' => 'Hasil',
    'success' => 'berhasil',
    'updated' => 'diperbarui',
    'failed' => 'gagal',
    'status' => 'Status',
    'actions' => 'Aksi',

    // Status
    'status_pending' => 'Menunggu',
    'status_processing' => 'Memproses',
    'status_completed' => 'Selesai',
    'status_failed' => 'Gagal',

    // Detail
    'detail_title' => 'Detail Import',
    'summary' => 'Ringkasan',
    'total_data' => 'Total Data',
    'success_count' => 'Berhasil',
    'error_count' => 'Gagal',
    'skipped_count' => 'Diperbarui',
    'success_rate' => 'Tingkat Keberhasilan',
    'error_details' => 'Detail Error',
    'row' => 'Baris',
    'error_message' => 'Pesan Error',

    // Empty state
    'no_history' => 'Belum ada riwayat import',

    // Actions
    'import_data' => 'Import Data',
    'import_again' => 'Import Lagi',
    'delete_log' => 'Hapus Log',
    'delete_confirm' => 'Yakin ingin menghapus log import ini?',
    'upload_again' => 'Upload Ulang',
    'column_description' => 'Keterangan Kolom',
    'template' => 'Template',
    'and_more_rows' => 'dan :count baris lainnya',
    'of_rows' => 'dari :total baris',
    'imported_by' => 'Diimport oleh',
    'import_time' => 'Waktu import',
    'duration' => 'Durasi',
    'file_invalid' => 'File tidak valid',
    'file_valid' => 'File valid',
    'rows_to_import' => 'Ditemukan :count baris data yang akan diimport',
    'import_import' => 'Import',
    'import_entity' => 'Import :entity',
    'total_rows_label' => 'Total Baris',
    'data' => 'Data',
    'and_other' => 'dan :count lainnya',

    // Messages
    'upload_success' => 'File berhasil diunggah.',
    'import_success' => 'Import :entity berhasil. :success data berhasil diimport',
    'import_success_with_update' => ', :skipped data diperbarui',
    'import_success_with_error' => ', :error data gagal',
    'import_failed' => 'Import gagal. Silakan periksa detail error.',
    'file_not_found' => 'File tidak ditemukan. Silakan unggah ulang.',
    'log_deleted' => 'Log import berhasil dihapus.',

    // Validation
    'file_required' => 'File Excel wajib diunggah.',
    'file_mimes' => 'File harus berformat Excel (.xlsx atau .xls).',
    'file_max' => 'Ukuran file maksimal 10MB.',
    'invalid_entity' => 'Jenis import tidak valid.',
];
