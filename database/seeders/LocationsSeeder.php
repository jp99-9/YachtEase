<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
            Location::create(['name' => 'Proa', 'description' => 'Parte frontal', 'latitude' => '36.1627', 'longitude' => '-5.3536', 'boat_id' => 1]);
            Location::create(['name' => 'Popa', 'description' => 'Parte trasera', 'latitude' => '36.1627', 'longitude' => '-5.3540', 'boat_id' => 2]);
        
            
    }
}
