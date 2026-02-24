<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Owner Clinic',
                'email' => 'owner@jagoflutter.com',
                'password' => bcrypt('password'),
                'role' => 'owner',
                'business_type' => 'clinic',
                'phone' => '081234567890',
                'is_active' => true,
            ],
            [
                'name' => 'Owner Gym',
                'email' => 'owner.gym@jagoflutter.com',
                'password' => bcrypt('password'),
                'role' => 'owner',
                'business_type' => 'gym',
                'phone' => '081234567895',
                'is_active' => true,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@jagoflutter.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '081234567891',
                'is_active' => true,
            ],
            [
                'name' => 'Maya',
                'email' => 'maya@jagoflutter.com',
                'password' => bcrypt('password'),
                'role' => 'beautician',
                'phone' => '081234567892',
                'is_active' => true,
            ],
            [
                'name' => 'Lisa',
                'email' => 'lisa@jagoflutter.com',
                'password' => bcrypt('password'),
                'role' => 'beautician',
                'phone' => '081234567893',
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Sarah',
                'email' => 'sarah@jagoflutter.com',
                'password' => bcrypt('password'),
                'role' => 'beautician',
                'phone' => '081234567894',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
