<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Type::create(['name' => 'Herramienta', 'description' => 'Herramienta de uso general']);
        Type::create(['name' => 'Material','description' => 'Material de uso general']);
        Type::create(['name' => 'Equipo','description' => 'Equipo de uso general']);
        Type::create(['name' => 'Consumible','description' => 'Consumible de uso general']); 
        Type::create(['name' => 'Equipo','description' => 'Equipo de uso general']);

        Type::factory(0)->create();
    }
}
