<?php

return [
    'title' => 'Products',
    'subtitle' => 'Manage your product inventory',
    'category_title' => 'Product Categories',
    'category_subtitle' => 'Manage product categories',

    // Fields
    'name' => 'Product Name',
    'sku' => 'SKU',
    'category' => 'Category',
    'description' => 'Description',
    'price' => 'Selling Price',
    'cost_price' => 'Cost Price',
    'stock' => 'Stock',
    'min_stock' => 'Minimum Stock',
    'unit' => 'Unit',
    'image' => 'Image',
    'track_stock' => 'Track Stock',

    // Units
    'unit_pcs' => 'pcs',
    'unit_box' => 'box',
    'unit_bottle' => 'bottle',
    'unit_tube' => 'tube',
    'unit_pack' => 'pack',
    'unit_set' => 'set',

    // Placeholders
    'search_placeholder' => 'Search by name or SKU...',
    'select_category' => 'Select category',
    'all_categories' => 'All Categories',

    // Status
    'all_stock' => 'All Stock',
    'low_stock' => 'Low Stock',
    'out_of_stock' => 'Out of Stock',
    'in_stock' => 'In Stock',

    // Messages
    'created' => 'Product created successfully.',
    'updated' => 'Product updated successfully.',
    'deleted' => 'Product deleted successfully.',
    'activated' => 'Product has been activated.',
    'deactivated' => 'Product has been deactivated.',
    'has_transactions' => 'Product cannot be deleted because it has transactions.',
    'sku_exists' => 'This SKU is already in use.',
    'stock_adjusted' => 'Stock has been adjusted.',
    'stock_insufficient' => 'Insufficient stock.',

    // Category Messages
    'category_created' => 'Product category created successfully.',
    'category_updated' => 'Product category updated successfully.',
    'category_deleted' => 'Product category deleted successfully.',
    'category_has_products' => 'Category cannot be deleted because it has products.',
    'category_reordered' => 'Category order updated successfully.',

    // Actions
    'add_product' => 'Add Product',
    'edit_product' => 'Edit Product',
    'add_category' => 'Add Category',
    'edit_category' => 'Edit Category',
    'adjust_stock' => 'Adjust Stock',
    'view_categories' => 'View Categories',

    // Labels
    'no_products' => 'No products found.',
    'no_categories' => 'No categories found.',
    'product_count' => ':count product|:count products',
    'low_stock_alert' => ':count products with low stock',

    // Stock Adjustment
    'stock_adjustment' => 'Stock Adjustment',
    'adjustment_amount' => 'Adjustment Amount',
    'adjustment_reason' => 'Reason',
    'adjustment_hint' => 'Use positive number to add, negative to reduce',
];
