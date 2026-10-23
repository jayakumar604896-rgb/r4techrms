<?php

namespace Database\Seeders;

use App\Models\PlacementType;
use Illuminate\Database\Seeder;

class PlacementTypeSeeder extends Seeder
{
    public function run()
    {
        $placementTypes = [
            ['name' => 'Executive Staff Placements', 'slug' => 'executive-staff-placements'],
            ['name' => 'Project Workforce', 'slug' => 'project-workforce'],
            ['name' => 'Contract Staffing', 'slug' => 'contract-staffing'],
            ['name' => 'Bulk Manpower Hiring', 'slug' => 'bulk-manpower-hiring'],
            ['name' => 'Payroll Process', 'slug' => 'payroll-process'],
        ];

        foreach ($placementTypes as $type) {
            PlacementType::create($type);
        }
    }
}