<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // crear 99 usuarios aleatorios
        User::factory(99)->create();
    }
}
