<?php

return [
    'title' => 'Import Data',
    'subtitle' => 'Import data from Excel file to migrate data to GlowUp',

    // Entity types
    'customers' => 'Customers',
    'services' => 'Services',
    'packages' => 'Treatment Packages',

    // Entity descriptions
    'customers_desc' => 'Import customer data from Excel file',
    'services_desc' => 'Import services and categories data',
    'packages_desc' => 'Import treatment packages data',

    // Upload
    'upload_title' => 'Upload Excel File',
    'select_file' => 'Select File',
    'click_to_upload' => 'Click to upload',
    'drag_drop' => 'or drag & drop',
    'file_format' => 'Excel (.xlsx, .xls) max. 10MB',
    'upload_preview' => 'Upload & Preview',
    'download_template' => 'Download Excel Template',

    // Instructions
    'instructions' => 'Import Instructions',
    'file_format_title' => 'File Format',
    'format_excel' => 'File must be Excel format (.xlsx or .xls)',
    'format_header' => 'First row must contain column names',
    'format_size' => 'Maximum size 10MB',
    'format_sheet' => 'Data must be in the first sheet',
    'required_columns' => 'Required Columns',
    'available_columns' => 'Available Columns',
    'update_warning' => 'Data with existing phone number/name will be updated, not duplicated.',

    // Preview
    'preview_title' => 'Data Preview',
    'preview_subtitle' => 'Review data before import',
    'data_preview' => 'Data Preview',
    'total_rows' => ':count rows of data will be imported',
    'validation_ok' => 'Validation successful',
    'validation_error' => 'There are issues with the data',
    'back_to_upload' => 'Back to Upload',
    'process_import' => 'Process Import',

    // History
    'history' => 'Import History',
    'date' => 'Date',
    'data_type' => 'Data Type',
    'file' => 'File',
    'result' => 'Result',
    'success' => 'successful',
    'updated' => 'updated',
    'failed' => 'failed',
    'status' => 'Status',
    'actions' => 'Actions',

    // Status
    'status_pending' => 'Pending',
    'status_processing' => 'Processing',
    'status_completed' => 'Completed',
    'status_failed' => 'Failed',

    // Detail
    'detail_title' => 'Import Detail',
    'summary' => 'Summary',
    'total_data' => 'Total Data',
    'success_count' => 'Successful',
    'error_count' => 'Failed',
    'skipped_count' => 'Updated',
    'success_rate' => 'Success Rate',
    'error_details' => 'Error Details',
    'row' => 'Row',
    'error_message' => 'Error Message',

    // Empty state
    'no_history' => 'No import history',

    // Actions
    'import_data' => 'Import Data',
    'import_again' => 'Import Again',
    'delete_log' => 'Delete Log',
    'delete_confirm' => 'Are you sure you want to delete this import log?',
    'upload_again' => 'Upload Again',
    'column_description' => 'Column Description',
    'template' => 'Template',
    'and_more_rows' => 'and :count more rows',
    'of_rows' => 'of :total rows',
    'imported_by' => 'Imported by',
    'import_time' => 'Import time',
    'duration' => 'Duration',
    'file_invalid' => 'File invalid',
    'file_valid' => 'File valid',
    'rows_to_import' => ':count rows of data will be imported',
    'import_import' => 'Import',
    'import_entity' => 'Import :entity',
    'total_rows_label' => 'Total Rows',
    'data' => 'Data',
    'and_other' => 'and :count others',

    // Messages
    'upload_success' => 'File uploaded successfully.',
    'import_success' => ':entity import successful. :success data imported successfully',
    'import_success_with_update' => ', :skipped data updated',
    'import_success_with_error' => ', :error data failed',
    'import_failed' => 'Import failed. Please check error details.',
    'file_not_found' => 'File not found. Please upload again.',
    'log_deleted' => 'Import log successfully deleted.',

    // Validation
    'file_required' => 'Excel file is required.',
    'file_mimes' => 'File must be Excel format (.xlsx or .xls).',
    'file_max' => 'Maximum file size is 10MB.',
    'invalid_entity' => 'Invalid import type.',
];
