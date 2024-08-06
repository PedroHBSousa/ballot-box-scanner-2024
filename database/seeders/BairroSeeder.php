<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BairroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bairros')->insert([
            ['nome' => 'barequecaba', 'regiao' => 'centro'],
            ['nome' => 'barra do sahy', 'regiao' => 'costa sul'],
            ['nome' => 'barra do una', 'regiao' => 'costa sul'],
            ['nome' => 'boicucanga', 'regiao' => 'costa sul'],
            ['nome' => 'boraceia', 'regiao' => 'costa sul'],
            ['nome' => 'camburi', 'regiao' => 'costa sul'],
            ['nome' => 'canto do mar', 'regiao' => 'costa norte'],
            ['nome' => 'centro', 'regiao' => 'centro'],
            ['nome' => 'enseada', 'regiao' => 'costa norte'],
            ['nome' => 'itatinga', 'regiao' => 'centro'],
            ['nome' => 'jaragua', 'regiao' => 'costa norte'],
            ['nome' => 'juquehy', 'regiao' => 'costa sul'],
            ['nome' => 'maresias', 'regiao' => 'costa sul'],
            ['nome' => 'morro do abrigo', 'regiao' => 'centro'],
            ['nome' => 'pauba', 'regiao' => 'costa sul'],
            ['nome' => 'pontal da cruz', 'regiao' => 'centro'],
            ['nome' => 'porto grande', 'regiao' => 'centro'],
            ['nome' => 'sao francisco', 'regiao' => 'centro'],
            ['nome' => 'topolandia', 'regiao' => 'centro'],
            ['nome' => 'toque-toque pequeno', 'regiao' => 'costa sul'],
            ['nome' => 'vila amelia', 'regiao' => 'centro'],
        ]);
    }
}
