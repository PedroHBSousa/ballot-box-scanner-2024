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
        Schema::create('boletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secao_id')->references('id')->on('secoes');
            // $table->foreign('secao_id')->references('id')->on('secoes');
            $table->integer('apto');
            $table->integer('comp');
            $table->integer('falt');
            $table->string('assinatura_digital');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletins');
    }
};
