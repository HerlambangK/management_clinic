<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Referral Program Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable the referral program.
    |
    */
    'enabled' => env('REFERRAL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Referrer Bonus Points
    |--------------------------------------------------------------------------
    |
    | Points awarded to the referrer (the customer who shared their code)
    | when their referred customer makes their first paid transaction.
    |
    */
    'referrer_bonus_points' => env('REFERRAL_REFERRER_POINTS', 100),

    /*
    |--------------------------------------------------------------------------
    | Referee Bonus Points
    |--------------------------------------------------------------------------
    |
    | Points awarded to the referee (the new customer who used the code)
    | when they make their first paid transaction.
    |
    */
    'referee_bonus_points' => env('REFERRAL_REFEREE_POINTS', 50),

    /*
    |--------------------------------------------------------------------------
    | Minimum Transaction Amount
    |--------------------------------------------------------------------------
    |
    | Minimum transaction amount required to trigger the referral reward.
    | Set to 0 to disable minimum requirement.
    |
    */
    'min_transaction_amount' => env('REFERRAL_MIN_TRANSACTION', 0),

    /*
    |--------------------------------------------------------------------------
    | Referral Code Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for auto-generated referral codes.
    |
    */
    'code_prefix' => 'REF',
];
