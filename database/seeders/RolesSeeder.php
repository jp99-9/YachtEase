<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Capitán', 'Marinero', 'Steward', 'Ingeniero'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
