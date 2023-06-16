<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory(1)->create();
        foreach ($admin as $key => $value) {
            $admin[$key]->assignRole(1);
        }

        $standarUsers = User::factory(5)->create();
        foreach ($standarUsers as $key => $value) {
            $standarUsers[$key]->assignRole(2);
        }
    }
}
