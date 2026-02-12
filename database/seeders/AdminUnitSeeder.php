<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class AdminUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin unit with ID = 1
        Unit::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Admin Office',
            ]
        );
    }
}