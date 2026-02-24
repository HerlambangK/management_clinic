<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Points Earning Rate
    |--------------------------------------------------------------------------
    |
    | The amount in IDR that equals 1 loyalty point.
    | Default: 10000 means customer earns 1 point for every Rp 10,000 spent.
    |
    */
    'points_per_amount' => env('LOYALTY_POINTS_PER_AMOUNT', 10000),

    /*
    |--------------------------------------------------------------------------
    | Points Expiry
    |--------------------------------------------------------------------------
    |
    | Number of months until earned points expire. Set to null for no expiry.
    | Default: 12 months
    |
    */
    'points_expiry_months' => env('LOYALTY_POINTS_EXPIRY_MONTHS', 12),

    /*
    |--------------------------------------------------------------------------
    | Loyalty Tiers
    |--------------------------------------------------------------------------
    |
    | Define the lifetime points required for each tier.
    | Tiers are checked from lowest to highest.
    |
    */
    'tiers' => [
        'bronze' => 0,
        'silver' => 1000,
        'gold' => 5000,
        'platinum' => 10000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tier Multipliers
    |--------------------------------------------------------------------------
    |
    | Bonus points multiplier for each tier.
    | Example: 1.5 means 50% bonus points
    |
    */
    'tier_multipliers' => [
        'bronze' => 1.0,
        'silver' => 1.1,
        'gold' => 1.25,
        'platinum' => 1.5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redemption Code Validity
    |--------------------------------------------------------------------------
    |
    | Number of days a redemption code is valid after creation.
    | Default: 30 days
    |
    */
    'redemption_validity_days' => env('LOYALTY_REDEMPTION_VALIDITY_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Points Value (for direct redemption)
    |--------------------------------------------------------------------------
    |
    | The value in IDR of 1 loyalty point when used as discount.
    | Default: 100 means 1 point = Rp 100 discount.
    | Example: 100 points = Rp 10,000 discount
    |
    */
    'points_value' => env('LOYALTY_POINTS_VALUE', 100),

    /*
    |--------------------------------------------------------------------------
    | Minimum Points to Redeem
    |--------------------------------------------------------------------------
    |
    | Minimum points required to use as discount in transaction.
    | Default: 10 points
    |
    */
    'min_points_redeem' => env('LOYALTY_MIN_POINTS_REDEEM', 10),
];
