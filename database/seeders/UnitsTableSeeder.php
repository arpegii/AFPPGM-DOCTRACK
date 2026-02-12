<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['id' => 1, 'name' => 'ADMN'], // Admin unit (hidden from regular users)
            ['id' => 2, 'name' => 'BGCU'],
            ['id' => 3, 'name' => 'CIU'],
            ['id' => 4, 'name' => 'COMMAND'],
            ['id' => 5, 'name' => 'ISU'],
            ['id' => 6, 'name' => 'LSO'],
            ['id' => 7, 'name' => 'PAU'],
            ['id' => 8, 'name' => 'PG1'],
            ['id' => 9, 'name' => 'PG3'],
            ['id' => 10, 'name' => 'PG4'],
            ['id' => 11, 'name' => 'PG10'],
            ['id' => 12, 'name' => 'PPBU'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['id' => $unit['id']],
                ['name' => $unit['name']]
            );
            
            $this->command->info("Unit created: {$unit['name']}");
        }
        
        $this->command->newLine();
        $this->command->info('All units created successfully!');
    }
}