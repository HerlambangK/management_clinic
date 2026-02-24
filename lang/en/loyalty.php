<?php

return [
    // Page Titles
    'title' => 'Loyalty Program',
    'subtitle' => 'Manage customer points and rewards',
    'customers_title' => 'Loyalty Customers',
    'customers_subtitle' => 'View customers by loyalty points',
    'redemptions_title' => 'Redemptions',
    'redemptions_subtitle' => 'Manage reward redemptions',
    'rewards_title' => 'Manage Rewards',
    'rewards_subtitle' => 'Create and manage available rewards',
    'customer_history_title' => ':name\'s Points History',

    // Navigation
    'customers' => 'Customers',
    'redemptions' => 'Redemptions',
    'manage_rewards' => 'Manage Rewards',

    // Stats
    'total_earned' => 'Total Points Earned',
    'total_redeemed' => 'Total Points Redeemed',
    'active_customers' => 'Active Customers',
    'pending_redemptions' => 'Pending',
    'used_redemptions' => 'Used',
    'total_redemptions' => 'Total Redemptions',
    'total_rewards' => 'Total Rewards',
    'active_rewards' => 'Active Rewards',

    // Table Headers
    'date' => 'Date',
    'customer' => 'Customer',
    'type' => 'Type',
    'points' => 'Points',
    'balance' => 'Balance',
    'description' => 'Description',
    'tier' => 'Tier',
    'current_points' => 'Current Points',
    'lifetime_points' => 'Lifetime Points',
    'code' => 'Code',
    'reward' => 'Reward',
    'points_used' => 'Points Used',
    'valid_until' => 'Valid Until',
    'points_required' => 'Points Required',
    'value' => 'Value',
    'stock' => 'Stock',

    // Filters
    'search_customer' => 'Search customer name or phone...',
    'search_code_or_customer' => 'Search code or customer name...',
    'search_reward' => 'Search reward...',
    'all_types' => 'All Types',
    'all_tiers' => 'All Tiers',
    'all_status' => 'All Status',

    // Customer History
    'points_history' => 'Points History',
    'available_rewards' => 'Available Rewards',
    'active_redemptions' => 'Active Redemptions',
    'member_since' => 'Member since :date',
    'adjust_points' => 'Adjust Points',
    'points_amount' => 'Points amount (positive/negative)',
    'adjust_points_help' => 'Use positive value to add, negative to deduct',
    'reason' => 'Reason',
    'adjust' => 'Adjust',
    'lifetime' => 'Lifetime',

    // Redemption
    'enter_code' => 'Enter redemption code...',
    'use_code' => 'Use Code',
    'use' => 'Use',
    'redeem' => 'Redeem',
    'confirm_redeem' => 'Are you sure you want to redeem this reward?',
    'confirm_cancel' => 'Are you sure you want to cancel?',

    // Rewards Form
    'add_reward' => 'Add Reward',
    'edit_reward' => 'Edit Reward',
    'reward_name' => 'Reward Name',
    'reward_name_placeholder' => 'Example: 10% Discount',
    'description_placeholder' => 'Reward description...',
    'reward_type' => 'Reward Type',
    'select_type' => 'Select reward type',
    'reward_value' => 'Reward Value',
    'discount_percent_help' => 'Enter discount percentage (e.g., 10 for 10%)',
    'discount_amount_help' => 'Enter discount value in Rupiah',
    'select_service' => 'Select Service',
    'select_product' => 'Select Product',
    'unlimited' => 'Unlimited',
    'stock_help' => 'Leave empty for unlimited stock',
    'max_per_customer' => 'Max per Customer',
    'max_per_customer_help' => 'Leave empty for no limit',
    'valid_from' => 'Valid From',

    // Messages - Success
    'reward_created' => 'Reward created successfully.',
    'reward_updated' => 'Reward updated successfully.',
    'reward_deleted' => 'Reward deleted successfully.',
    'reward_activated' => 'Reward has been activated.',
    'reward_deactivated' => 'Reward has been deactivated.',
    'points_adjusted' => 'Points adjusted successfully.',
    'redeem_success' => 'Successfully redeemed :reward. Code: :code',
    'code_used_success' => 'Reward :reward for :customer used successfully.',
    'redemption_cancelled' => 'Redemption cancelled successfully.',

    // Messages - Error
    'cannot_redeem' => 'Cannot redeem this reward.',
    'insufficient_points' => 'Insufficient points.',
    'code_not_found' => 'Code not found.',
    'code_already_used' => 'Code has already been used.',
    'code_expired' => 'Code has expired.',
    'code_cancelled' => 'Code has been cancelled.',
    'code_invalid' => 'Code is not valid.',
    'code_valid' => 'Code is valid and ready to use.',
    'cannot_cancel_redemption' => 'Redemption cannot be cancelled.',
    'reward_has_redemptions' => 'Reward cannot be deleted because it has redemptions.',
    'valid_until_after_from' => 'End date must be after start date.',

    // Point Description Templates
    'points_from_transaction' => 'Points from transaction :invoice',
    'redeemed_for' => 'Redeemed for :reward',
    'points_refunded' => 'Points refunded for :reward',

    // Empty States
    'no_history' => 'No points history yet',
    'no_customers' => 'No customer data yet',
    'no_redemptions' => 'No redemptions yet',
    'no_rewards' => 'No rewards yet',
    'no_available_rewards' => 'No rewards available',
    'no_active_redemptions' => 'No active redemptions',

    // Customer Show Page
    'loyalty_points' => 'Loyalty Points',
    'earn_info' => 'Earn 1 point for every Rp :points spent',

    // Referrals
    'referrals' => 'Referrals',
    'referrals_title' => 'Referral Program',
    'referrals_subtitle' => 'View all customer referrals',
    'referrer' => 'Referrer',
    'referee' => 'Referred',
    'referrer_points' => 'Referrer Points',
    'referee_points' => 'Referred Points',
    'total_referrals' => 'Total Referrals',
    'pending_referrals' => 'Awaiting Transaction',
    'rewarded_referrals' => 'Rewarded',
    'total_points_given' => 'Total Points Given',
    'search_referral' => 'Search name or phone...',
    'no_referrals' => 'No referral data yet',
    'referral_bonus_referrer' => 'Referral bonus - invited :name',
    'referral_bonus_referee' => 'Referral bonus - invited by :name',
    'your_referral_code' => 'Your Referral Code',
    'copy_code' => 'Copy Code',
    'share_code' => 'Share Code',
    'referral_stats' => 'Referral Stats',
    'successful_referrals' => 'Successful Referrals',
    'total_bonus_earned' => 'Total Bonus Earned',

    // Customer referral section
    'referral_code' => 'Referral Code',
    'share_referral_code' => 'Share this code to earn bonus points',
    'total_bonus_points' => 'Total Bonus Points',
    'pending' => 'Pending',
    'rewarded' => 'Rewarded',
    'code_copied' => 'Code copied successfully!',

    // Points redemption in transaction
    'use_points' => 'Use Points',
    'available' => 'Available',
    'points' => 'points',
    'use_max' => 'Use Maximum',
    'points_discount' => 'Points Discount',
    'points_used_transaction' => 'Used for transaction :invoice',
    'points_refunded_transaction' => 'Points refunded from transaction :invoice',
];
