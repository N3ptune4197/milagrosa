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
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 30);
            $table->unsignedBigInteger('id_categoria');
            $table->tinyInteger('estado');
            $table->timestamp('fecha_registro');
            $table->string('modelo', 30);
            $table->string('nro_serie', 20);
            $table->unsignedBigInteger('id_marca');
            $table->foreign('id_categoria')->references('id')->on('categorias');
            $table->foreign('id_marca')->references('id')->on('marcas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
