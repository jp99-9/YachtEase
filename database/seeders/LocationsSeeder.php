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
            Location::create(['name' => 'Proa', 'description' => 'Parte frontal', 'latitude' => '49.1627', 'longitude' => '82.3536', 'boat_id' => 1]);
            Location::create(['name' => 'Popa', 'description' => 'Parte trasera', 'latitude' => '49.1627', 'longitude' => '08.3540', 'boat_id' => 1]);
            Location::create(['name' => 'Cockpit', 'description' => 'Sala de comandos', 'latitude' => '36.1227', 'longitude' => '56.3536', 'boat_id' => 1]);
            Location::create(['name' => 'Sentina', 'description' => 'Parte profunda del yate', 'latitude' => '63.1627', 'longitude' => '52.3540', 'boat_id' => 1]);
            Location::create(['name' => 'Cabin Crew', 'description' => 'Parte interior del yate', 'latitude' => '32.1627', 'longitude' => '66.3536', 'boat_id' => 1]);   
        
        //TENGO QUE MOIDIFICAR ESTO PORQUE DENTRO DE CADA ZONA HAY SUBZONAS A TENER EN CUENTA. POR EJEMPOL DENTRO DE LA SNETINA ESTA LA SALA DE MAQUINAS.    
    }
}
