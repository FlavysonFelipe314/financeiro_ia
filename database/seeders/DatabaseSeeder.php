<?php

namespace Database\Seeders;

use App\Models\Conta;
use App\Models\Entrada;
use App\Models\Saida;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Conta::factory(5)->create();

        Entrada::factory(20)->create();
        Saida::factory(20)->create();
    }
}
