<?php

namespace Database\Seeders;

use App\Models\Boat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BoatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Boat::create([
            'name' => 'Aurora',
            'house' => 'Sunseeker',
            'size' => '30m',
            'incorporation_date' => now(),
            'password' => Hash::make('1234'),
            'unique_code' => 'AURORA123',
        ]);
    
        Boat::create([
            'name' => 'Odyssey',
            'house' => 'Princess',
            'size' => '40m',
            'incorporation_date' => now(),
            'password' => Hash::make('1234'),
            'unique_code' => 'ODYSSEY123',
        ]);

        Boat::factory(10)->create();
    }
}
