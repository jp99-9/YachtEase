<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create(['name' => 'Capitán Juan', 'avatar' => 'captain.png', 'status' => 'active', 'user_id' => 1, 'boat_id' => 1]);
        Profile::create(['name' => 'Marinero Ana', 'avatar' => 'sailor.png', 'status' => 'active', 'user_id' => 2, 'boat_id' => 2]);

        Profile::factory(5)->create();
    }
}
