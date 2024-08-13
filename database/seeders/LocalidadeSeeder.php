<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('localidades')->insert([
            ['id' => '1015','bairro_id' => '17', 'nome' => 'ETEC - Escola Técnica Estadual de São Paulo'],
            ['id' => '1023','bairro_id' => '8', 'nome' => 'EE Prof Maisa Theodoro da Silva'],
            ['id' => '1031','bairro_id' => '16', 'nome' => 'EE Prof Maria Francisca Tavolaro'],
            ['id' => '1040','bairro_id' => '18', 'nome' => 'EE Prof Nair Ferreira Neves'],
            ['id' => '1066','bairro_id' => '19', 'nome' => 'EE Prof Josefa de Santana Neves'],
            ['id' => '1074','bairro_id' => '13', 'nome' => 'EE Prof Dulce Cezar Tavares'],
            ['id' => '1082','bairro_id' => '4', 'nome' => 'EE Walkir Vergani'],
            ['id' => '1090','bairro_id' => '12', 'nome' => 'EE Plinio Gonçalves Oliveira'],
            ['id' => '1112','bairro_id' => '16', 'nome' => 'EMEI Algodão Doce'],
            ['id' => '1120','bairro_id' => '9', 'nome' => 'EMEI Solange de Paula'],
            ['id' => '1147','bairro_id' => '3', 'nome' => 'EE Prof Sebastiana Costa Bittencourt'],
            ['id' => '1155','bairro_id' => '10', 'nome' => 'EM Prof Irayes Lobo Vianna Rego'],
            ['id' => '1163','bairro_id' => '14', 'nome' => 'Creche Dona Laurinda'],
            ['id' => '1171','bairro_id' => '5', 'nome' => 'EM Prof Vilma Aparecida '],
            ['id' => '1180','bairro_id' => '4', 'nome' => 'EM Guiomar Aparecida'],
            ['id' => '1198','bairro_id' => '6', 'nome' => 'EM Prof Lino Marques - Sementinha'],
            ['id' => '1201','bairro_id' => '7', 'nome' => 'EM Profª Joana Alves'],
            ['id' => '1228','bairro_id' => '12', 'nome' => 'EM Nair Ribeiro'],
            ['id' => '1236','bairro_id' => '20', 'nome' => 'EM João Gabriel'],
            ['id' => '1260','bairro_id' => '1', 'nome' => 'EMEI Arco-Iris'],
            ['id' => '1279','bairro_id' => '18', 'nome' => 'EMEI Chapeuzinho Vermelho'],
            ['id' => '1287','bairro_id' => '2', 'nome' => 'EM Henrique Tavares'],
            ['id' => '1309','bairro_id' => '21', 'nome' => 'EMEI Emilia Pinder (Peteleco)'],
            ['id' => '1317','bairro_id' => '19', 'nome' => 'EM Verena de Oliveira Dória'],
            ['id' => '1325','bairro_id' => '11', 'nome' => 'EMEI Maria Alice Rangel - Elefante Colorido'],
            ['id' => '1341','bairro_id' => '13', 'nome' => 'EM Prof Edileusa Brasil'],
            ['id' => '1350','bairro_id' => '4', 'nome' => 'EM Prof Antonio Luiz Monteiro'],
            ['id' => '1368','bairro_id' => '9', 'nome' => 'EM Prof Cynthia Cliquet Luciano'],
            ['id' => '1376','bairro_id' => '19', 'nome' => 'EM Patricia Viviani Santana'],
            ['id' => '1384','bairro_id' => '18', 'nome' => 'Centro Cultural Batuira'],
            ['id' => '1392','bairro_id' => '15', 'nome' => 'Associação de Moradores de Paúba'],
            ['id' => '1406','bairro_id' => '6', 'nome' => 'EM Maria da Conceição de Deus Santos'],
            ['id' => '1430','bairro_id' => '13', 'nome' => 'Creche de Maresias'],
        ]);
    }
}
