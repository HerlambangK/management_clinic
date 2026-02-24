<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Base data
            UserSeeder::class,
            StoreSeeder::class,
            ServiceCategorySeeder::class,
            ServiceSeeder::class,
            SettingSeeder::class,
            OperatingHourSeeder::class,

            // Customers
            CustomerSeeder::class,

            // Packages
            PackageSeeder::class,

            // Products
            ProductCategorySeeder::class,
            ProductSeeder::class,

            // Appointments (depends on customers, services, users)
            AppointmentSeeder::class,

            // Transactions (depends on appointments, packages, customers)
            TransactionSeeder::class,
        ]);
    }
}
