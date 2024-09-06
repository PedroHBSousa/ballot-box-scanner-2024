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
            ['nome' => 'barequecaba'],
            ['nome' => 'barra do sahy'],
            ['nome' => 'barra do una'],
            ['nome' => 'boicucanga'],
            ['nome' => 'boraceia'],
            ['nome' => 'camburi'],
            ['nome' => 'canto do mar'],
            ['nome' => 'centro'],
            ['nome' => 'enseada'],
            ['nome' => 'itatinga'],
            ['nome' => 'jaragua'],
            ['nome' => 'juquehy'],
            ['nome' => 'maresias'],
            ['nome' => 'morro do abrigo'],
            ['nome' => 'pauba'],
            ['nome' => 'pontal da cruz'],
            ['nome' => 'porto grande'],
            ['nome' => 'sao francisco'],
            ['nome' => 'topolandia'],
            ['nome' => 'toque-toque pequeno'],
            ['nome' => 'vila amelia'],
        ]);
    }
}
