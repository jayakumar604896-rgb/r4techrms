<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run()
    {
        $industries = [
            ['name' => 'Manufacture', 'slug' => 'manufacture'],
            ['name' => 'Construction', 'slug' => 'construction'],
            ['name' => 'Engg. & Projects', 'slug' => 'engg-projects'],
            ['name' => 'Maintenance', 'slug' => 'maintenance'],
            ['name' => 'Operation, Testing & commissioning', 'slug' => 'operation-testing-commissioning'],
            ['name' => 'Hotels', 'slug' => 'hotels'],
            ['name' => 'Hospitals', 'slug' => 'hospitals'],
            ['name' => 'Others', 'slug' => 'others'],
        ];

        foreach ($industries as $industry) {
            Industry::create($industry);
        }
    }
}