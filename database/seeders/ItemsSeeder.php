<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Type;
use App\Models\Location;
use App\Models\StorageBox;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Type::count() === 0) {
            Type::factory()->count(3)->create();
        }

        if (Location::count() === 0) {
            Location::factory()->count(5)->create();
        }

        if (StorageBox::count() === 0) {
            StorageBox::factory()->count(5)->create();
        }
        
        Item::factory(50)->create();
    }
}
