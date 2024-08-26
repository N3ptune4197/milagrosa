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
        Schema::create('detalleprestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idprestamo')->constrained('prestamos');
            $table->foreignId('id_recurso')->constrained('recursos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalleprestamos');
    }
};
