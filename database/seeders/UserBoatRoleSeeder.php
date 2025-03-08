<?php

namespace Database\Seeders;

use App\Models\UserBoatRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserBoatRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserBoatRole::create([
            'user_id' => 1,
            'boat_id' => 1,
            'role_id' => 1, // Capitán
            'status' => 'active',
            'start_date' => now(),
        ]);
    
        UserBoatRole::create([
            'user_id' => 2,
            'boat_id' => 2,
            'role_id' => 2, // Marinero
            'status' => 'active',
            'start_date' => now(),
        ]);
    }
}
