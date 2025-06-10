<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Type;
use App\Models\Location;
use App\Models\StorageBox;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {

        $items = json_decode(file_get_contents(database_path('data/items.json')), true);

        foreach ($items as $item) {
            Item::create($item);
        }

        if (Type::count() === 0) {
            Type::factory()->count(3)->create();
        }

        if (Location::count() === 0) {
            Location::factory()->count(5)->create();
        }

        if (StorageBox::count() === 0) {
            StorageBox::factory()->count(5)->create();
        }

        Item::factory(0)->create();
    }
}
