<?php

namespace Database\Seeders;

use App\Models\StorageBox;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorageBoxesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StorageBox::create(['name' => 'Caja 1', 'description' => 'Caja defensas al lado del ancla', 'capacity' => 50, 'location_id' => 1]);
        StorageBox::create(['name' => 'Caja 2', 'description' => 'Caja de banderas', 'capacity' => 100, 'location_id' => 1]);
        StorageBox::create(['name' => 'Caja 3', 'description' => 'Caja de cabos', 'capacity' => 100, 'location_id' => 2]);
        StorageBox::create(['name' => 'Caja 4', 'description' => 'Caja documentos', 'capacity' => 100, 'location_id' => 3]);
        StorageBox::create(['name' => 'Caja 5', 'description' => 'Caja de herramientas', 'capacity' => 100, 'location_id' => 4]);
        
        StorageBox::factory(0)->create();
    }
}
