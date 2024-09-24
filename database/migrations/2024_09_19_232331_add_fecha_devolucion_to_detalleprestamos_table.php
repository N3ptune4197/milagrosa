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
        Schema::table('detalleprestamos', function (Blueprint $table) {
            $table->date('fecha_devolucion')->after('id_recurso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalleprestamos', function (Blueprint $table) {
            $table->dropColumn('fecha_devolucion');
        });
    }
};
