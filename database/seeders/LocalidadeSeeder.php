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
            ['id' => '1015', 'bairro_id' => '17', 'nome' => 'ETEC - Escola Técnica Estadual de São Paulo', 'secoes' => '6', 'eleitores' => '1852'],
            ['id' => '1023', 'bairro_id' => '8', 'nome' => 'EE Prof Maisa Theodoro da Silva', 'secoes' => '10', 'eleitores' => '3061'],
            ['id' => '1031', 'bairro_id' => '16', 'nome' => 'EE Prof Maria Francisca Tavolaro', 'secoes' => '7', 'eleitores' => '2474'],
            ['id' => '1040', 'bairro_id' => '18', 'nome' => 'EE Prof Nair Ferreira Neves', 'secoes' => '6', 'eleitores' => '1700'],
            ['id' => '1066', 'bairro_id' => '19', 'nome' => 'EE Prof Josefa de Santana Neves', 'secoes' => '6', 'eleitores' => '1357'],
            ['id' => '1074', 'bairro_id' => '13', 'nome' => 'EE Prof Dulce Cezar Tavares', 'secoes' => '5', 'eleitores' => '1453'],
            ['id' => '1082', 'bairro_id' => '4', 'nome' => 'EE Walkir Vergani', 'secoes' => '8', 'eleitores' => '2121'],
            ['id' => '1090', 'bairro_id' => '12', 'nome' => 'EE Plinio Gonçalves Oliveira', 'secoes' => '6', 'eleitores' => '1732'],
            ['id' => '1112', 'bairro_id' => '16', 'nome' => 'EMEI Algodão Doce', 'secoes' => '3', 'eleitores' => '869'],
            ['id' => '1120', 'bairro_id' => '9', 'nome' => 'EMEI Solange de Paula', 'secoes' => '8', 'eleitores' => '2133'],
            ['id' => '1147', 'bairro_id' => '3', 'nome' => 'EE Prof Sebastiana Costa Bittencourt', 'secoes' => '5', 'eleitores' => '1736'],
            ['id' => '1155', 'bairro_id' => '10', 'nome' => 'EM Prof Irayes Lobo Vianna Rego', 'secoes' => '8', 'eleitores' => '2951'],
            ['id' => '1163', 'bairro_id' => '14', 'nome' => 'Creche Dona Laurinda', 'secoes' => '4', 'eleitores' => '1271'],
            ['id' => '1171', 'bairro_id' => '5', 'nome' => 'EM Prof Vilma Aparecida', 'secoes' => '5', 'eleitores' => '1302'],
            ['id' => '1180', 'bairro_id' => '4', 'nome' => 'EM Guiomar Aparecida', 'secoes' => '8', 'eleitores' => '2352'],
            ['id' => '1198', 'bairro_id' => '6', 'nome' => 'EM Prof Lino Marques - Sementinha', 'secoes' => '11', 'eleitores' => '4041'],
            ['id' => '1201', 'bairro_id' => '7', 'nome' => 'EM Profª Joana Alves', 'secoes' => '7', 'eleitores' => '2319'],
            ['id' => '1228', 'bairro_id' => '12', 'nome' => 'EM Nair Ribeiro', 'secoes' => '8', 'eleitores' => '2685'],
            ['id' => '1236', 'bairro_id' => '20', 'nome' => 'EM João Gabriel', 'secoes' => '3', 'eleitores' => '804'],
            ['id' => '1260', 'bairro_id' => '1', 'nome' => 'EMEI Arco-Iris', 'secoes' => '6', 'eleitores' => '1991'],
            ['id' => '1279', 'bairro_id' => '18', 'nome' => 'EMEI Chapeuzinho Vermelho', 'secoes' => '4', 'eleitores' => '1138'],
            ['id' => '1287', 'bairro_id' => '2', 'nome' => 'EM Henrique Tavares', 'secoes' => '7', 'eleitores' => '2479'],
            ['id' => '1309', 'bairro_id' => '21', 'nome' => 'EMEI Emilia Pinder (Peteleco)', 'secoes' => '3', 'eleitores' => '857'],
            ['id' => '1317', 'bairro_id' => '19', 'nome' => 'EM Verena de Oliveira Dória', 'secoes' => '9', 'eleitores' => '2609'],
            ['id' => '1325', 'bairro_id' => '11', 'nome' => 'EMEI Maria Alice Rangel - Elefante Colorido', 'secoes' => '3', 'eleitores' => '799'],
            ['id' => '1341', 'bairro_id' => '13', 'nome' => 'EM Prof Edileusa Brasil', 'secoes' => '5', 'eleitores' => '1773'],
            ['id' => '1350', 'bairro_id' => '4', 'nome' => 'EM Prof Antonio Luiz Monteiro', 'secoes' => '7', 'eleitores' => '2413'],
            ['id' => '1368', 'bairro_id' => '9', 'nome' => 'EM Prof Cynthia Cliquet Luciano', 'secoes' => '14', 'eleitores' => '4653'],
            ['id' => '1376', 'bairro_id' => '19', 'nome' => 'EM Patricia Viviani Santana', 'secoes' => '12', 'eleitores' => '3830'],
            ['id' => '1384', 'bairro_id' => '18', 'nome' => 'Centro Cultural Batuira', 'secoes' => '4', 'eleitores' => '1233'],
            ['id' => '1392', 'bairro_id' => '15', 'nome' => 'Associação de Moradores de Paúba', 'secoes' => '1', 'eleitores' => '287'],
            ['id' => '1406', 'bairro_id' => '6', 'nome' => 'EM Maria da Conceição de Deus Santos', 'secoes' => '1', 'eleitores' => '196'],
            ['id' => '1430', 'bairro_id' => '13', 'nome' => 'Creche de Maresias', 'secoes' => '6', 'eleitores' => '1966'],
        ]);
    }
}
