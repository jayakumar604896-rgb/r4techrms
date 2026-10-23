<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run()
    {
        $sectors = [
            ['name' => 'Oil gas / PCM', 'slug' => 'oil-gas-pcm'],
            ['name' => 'Power plants', 'slug' => 'power-plants'],
            ['name' => 'Cement Plants', 'slug' => 'cement-plants'],
            ['name' => 'Textile industries', 'slug' => 'textile-industries'],
            ['name' => 'Road & Fridge', 'slug' => 'road-fridge'],
            ['name' => 'Civil Building Constructions', 'slug' => 'civil-building-constructions'],
            ['name' => 'Automobile', 'slug' => 'automobile'],
            ['name' => 'Locomotive Sector', 'slug' => 'locomotive-sector'],
            ['name' => 'Marine & Shipping', 'slug' => 'marine-shipping'],
            ['name' => 'Mining Sector', 'slug' => 'mining-sector'],
            ['name' => 'Transport & logistics', 'slug' => 'transport-logistics'],
            ['name' => 'IT Sector', 'slug' => 'it-sector'],
            ['name' => 'Others', 'slug' => 'others'],
        ];

        foreach ($sectors as $sector) {
            Sector::create($sector);
        }
    }
}