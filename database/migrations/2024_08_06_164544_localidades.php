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
        Schema::create('localidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bairro_id')->references('id')->on('bairros');
            // $table->foreign('bairro_id')->references('id')->on('bairros');
            $table->string('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('localidades');
    }
};
