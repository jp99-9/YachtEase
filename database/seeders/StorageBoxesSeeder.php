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
        StorageBox::factory(10)->create();
    }
}
