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
            ['id' => '1015', 'bairro_id' => '17', 'regiao' => 'centro', 'nome' => 'ETEC - Escola Técnica Estadual de São Paulo', 'secoes' => '6', 'eleitores' => '1909'],
            ['id' => '1023', 'bairro_id' => '8', 'regiao' => 'centro', 'nome' => 'EE Prof Maisa Theodoro da Silva', 'secoes' => '10', 'eleitores' => '3152'],
            ['id' => '1031', 'bairro_id' => '16', 'regiao' => 'centro', 'nome' => 'EE Prof Maria Francisca Tavolaro', 'secoes' => '8', 'eleitores' => '2555'],
            ['id' => '1040', 'bairro_id' => '18', 'regiao' => 'centro', 'nome' => 'EE Prof Nair Ferreira Neves', 'secoes' => '6', 'eleitores' => '1784'],
            ['id' => '1066', 'bairro_id' => '19', 'regiao' => 'centro', 'nome' => 'EE Prof Josefa de Santana Neves', 'secoes' => '6', 'eleitores' => '1469'],
            ['id' => '1074', 'bairro_id' => '13', 'regiao' => 'costa sul', 'nome' => 'EE Prof Dulce Cezar Tavares', 'secoes' => '5', 'eleitores' => '1562'],
            ['id' => '1082', 'bairro_id' => '4', 'regiao' => 'costa sul', 'nome' => 'EE Walkir Vergani', 'secoes' => '8', 'eleitores' => '2300'],
            ['id' => '1090', 'bairro_id' => '12', 'regiao' => 'costa sul', 'nome' => 'EE Plinio Gonçalves Oliveira', 'secoes' => '15', 'eleitores' => '4654'],
            ['id' => '1112', 'bairro_id' => '16', 'regiao' => 'centro', 'nome' => 'EMEI Algodão Doce', 'secoes' => '3', 'eleitores' => '899'],
            ['id' => '1120', 'bairro_id' => '9', 'regiao' => 'costa norte', 'nome' => 'EMEI Solange de Paula', 'secoes' => '8', 'eleitores' => '2171'],
            ['id' => '1147', 'bairro_id' => '3', 'regiao' => 'costa sul', 'nome' => 'EE Prof Sebastiana Costa Bittencourt', 'secoes' => '5', 'eleitores' => '1846'],
            ['id' => '1155', 'bairro_id' => '10', 'regiao' => 'centro', 'nome' => 'EM Prof Irayes Lobo Vianna Rego', 'secoes' => '8', 'eleitores' => '3021'],
            ['id' => '1163', 'bairro_id' => '14', 'regiao' => 'centro', 'nome' => 'Creche Dona Laurinda', 'secoes' => '4', 'eleitores' => '1293'],
            ['id' => '1171', 'bairro_id' => '5', 'regiao' => 'costa sul', 'nome' => 'EM Prof Vilma Aparecida', 'secoes' => '5', 'eleitores' => '1423'],
            ['id' => '1180', 'bairro_id' => '4', 'regiao' => 'costa sul', 'nome' => 'EM Guiomar Aparecida', 'secoes' => '8', 'eleitores' => '2319'],
            ['id' => '1198', 'bairro_id' => '6', 'regiao' => 'costa sul', 'nome' => 'EM Prof Lino Marques - Sementinha', 'secoes' => '9', 'eleitores' => '3243'],
            ['id' => '1201', 'bairro_id' => '7', 'regiao' => 'costa norte', 'nome' => 'EM Profª Joana Alves', 'secoes' => '7', 'eleitores' => '2466'],
            ['id' => '1228', 'bairro_id' => '12', 'regiao' => 'costa sul', 'nome' => 'EM Nair Ribeiro', 'secoes' => '8', 'eleitores' => '2685'],
            ['id' => '1236', 'bairro_id' => '20', 'regiao' => 'costa sul', 'nome' => 'EM João Gabriel', 'secoes' => '3', 'eleitores' => '821'],
            ['id' => '1260', 'bairro_id' => '1', 'regiao' => 'centro', 'nome' => 'EMEI Arco-Iris', 'secoes' => '6', 'eleitores' => '2058'],
            ['id' => '1279', 'bairro_id' => '18', 'regiao' => 'centro', 'nome' => 'EMEI Chapeuzinho Vermelho', 'secoes' => '4', 'eleitores' => '1162'],
            ['id' => '1287', 'bairro_id' => '2', 'regiao' => 'costa sul', 'nome' => 'EM Henrique Tavares', 'secoes' => '7', 'eleitores' => '2664'],
            ['id' => '1309', 'bairro_id' => '21', 'regiao' => 'centro', 'nome' => 'EMEI Emilia Pinder (Peteleco)', 'secoes' => '3', 'eleitores' => '896'],
            ['id' => '1317', 'bairro_id' => '19', 'regiao' => 'centro', 'nome' => 'EM Verena de Oliveira Dória', 'secoes' => '9', 'eleitores' => '2667'],
            ['id' => '1325', 'bairro_id' => '11', 'regiao' => 'costa norte', 'nome' => 'EMEI Maria Alice Rangel - Elefante Colorido', 'secoes' => '3', 'eleitores' => '853'],
            ['id' => '1341', 'bairro_id' => '13', 'regiao' => 'costa sul', 'nome' => 'EM Prof Edileusa Brasil', 'secoes' => '5', 'eleitores' => '1744'],
            ['id' => '1350', 'bairro_id' => '4', 'regiao' => 'costa sul', 'nome' => 'EM Prof Antonio Luiz Monteiro', 'secoes' => '7', 'eleitores' => '2576'],
            ['id' => '1368', 'bairro_id' => '9', 'regiao' => 'costa norte', 'nome' => 'EM Prof Cynthia Cliquet Luciano', 'secoes' => '14', 'eleitores' => '4818'],
            ['id' => '1376', 'bairro_id' => '19', 'regiao' => 'centro', 'nome' => 'EM Patricia Viviani Santana', 'secoes' => '12', 'eleitores' => '3844'],
            ['id' => '1384', 'bairro_id' => '18', 'regiao' => 'centro', 'nome' => 'Centro Cultural Batuira', 'secoes' => '4', 'eleitores' => '1298'],
            ['id' => '1392', 'bairro_id' => '15', 'regiao' => 'costa sul', 'nome' => 'Associação de Moradores de Paúba', 'secoes' => '1', 'eleitores' => '307'],
            ['id' => '1406', 'bairro_id' => '6', 'regiao' => 'costa sul', 'nome' => 'EM Maria da Conceição de Deus Santos', 'secoes' => '4', 'eleitores' => '1123'],
            ['id' => '1430', 'bairro_id' => '13', 'regiao' => 'costa sul', 'nome' => 'Creche de Maresias', 'secoes' => '7', 'eleitores' => '2184'],
        ]);
    }
}
