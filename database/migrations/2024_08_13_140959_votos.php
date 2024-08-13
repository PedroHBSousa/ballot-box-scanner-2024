<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('votos', function (Blueprint $table){
            $table->id();
            $table->foreign('cargo_id')->references('id')->on('cargos');
            $table->foreign('boletim_id')->references('id')->on('boletins');
            $table->foreign('candidato_id')->references('id')->on('candidatos');
            $table->foreign('secao_id')->references('id')->on('secoes');
            $table->varchar('nominal');
            $table->varchar('nulo');
            $table->varchar('branco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votos');
    }
};
