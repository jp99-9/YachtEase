<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Movement;
use App\Models\StorageBox;
use App\Models\Type;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    { {
            $this->call([
                RolesSeeder::class,
                BoatsSeeder::class,
                UsersSeeder::class,
                UserBoatRoleSeeder::class,
                LocationsSeeder::class,
                ProfilesSeeder::class,
                ItemsSeeder::class,
                StorageBoxesSeeder::class,
                MovementsSeeder::class,
                TypesSeeder::class,

            ]);
        }

        // User::factory(10)->create();

        
    }
}
