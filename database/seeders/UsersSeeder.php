<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
{
    User::create(['first_name' => 'Juan', 'last_name' => 'Pérez', 'email' => 'juancpt@gmail.com']);
    User::create(['first_name' => 'Ana', 'last_name' => 'García', 'email' => 'anaslr@gmial.com']);

    User::factory(10)->create();
}

}
