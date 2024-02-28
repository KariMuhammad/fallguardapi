<?php

namespace Database\Seeders;

use App\Models\Caregiver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaregiverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Caregiver::factory()
            ->count(10)
            ->create();
    }
}
