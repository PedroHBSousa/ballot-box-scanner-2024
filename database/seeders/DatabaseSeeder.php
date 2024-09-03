<?php

namespace Database\Seeders;

use App\Models\Secao;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BairroSeeder::class,
            LocalidadeSeeder::class,
            CargoSeeder::class,
            CandidatoSeeder::class,
            SecaoSeeder::class,
        ]);
    }
}
