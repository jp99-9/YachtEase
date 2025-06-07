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
            Location::create(['name' => 'Proa', 'description' => 'Parte frontal', 'latitude' => '36.1627', 'longitude' => '5.3536', 'boat_id' => 1]);
            Location::create(['name' => 'Popa', 'description' => 'Parte trasera', 'latitude' => '66.1627', 'longitude' => '25.3540', 'boat_id' => 1]);
            Location::create(['name' => 'Cockpit', 'description' => 'Sala de comandos', 'latitude' => '55.1227', 'longitude' => '41.3536', 'boat_id' => 1]);
            Location::create(['name' => 'Sentina', 'description' => 'Parte profunda del yate', 'latitude' => '25.1627', 'longitude' => '60.3540', 'boat_id' => 1]);
            Location::create(['name' => 'Cabin Crew', 'description' => 'Parte interior del yate', 'latitude' => '6.1627', 'longitude' => '15.3536', 'boat_id' => 1]);   
        
        //TENGO QUE MOIDIFICAR ESTO PORQUE DENTRO DE CADA ZONA HAY SUBZONAS A TENER EN CUENTA. POR EJEMPOL DENTRO DE LA SNETINA ESTA LA SALA DE MAQUINAS.    
    }
}
