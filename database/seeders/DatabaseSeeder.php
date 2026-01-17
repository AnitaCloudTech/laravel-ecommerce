<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pozivamo seeder za korisnike
        $this->call(UsersTableSeeder::class);
    }
}
