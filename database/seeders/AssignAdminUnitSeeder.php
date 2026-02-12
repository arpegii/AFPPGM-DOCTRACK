<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Unit;

class AssignAdminUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUnit = Unit::adminUnit();
        
        if ($adminUnit) {
            // Assign admin unit to all admin users
            User::where('is_admin', true)
                ->update(['unit_id' => $adminUnit->id]);
            
            $this->command->info('Admin unit assigned to all admin users successfully!');
        } else {
            $this->command->error('Admin unit not found! Please run AdminUnitSeeder first.');
        }
    }
}