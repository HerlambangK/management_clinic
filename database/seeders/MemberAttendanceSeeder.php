<?php

namespace Database\Seeders;

use App\Models\MemberAttendance;
use Illuminate\Database\Seeder;

class MemberAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MemberAttendance::factory()->count(20)->create();
    }
}
