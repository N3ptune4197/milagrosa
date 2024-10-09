<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recursos_and_personals_tables', function (Blueprint $table) {
            // Modificar la tabla recursos
            Schema::table('recursos', function (Blueprint $table) {
                // Hacer que el campo nro_serie sea único
                $table->unique('nro_serie');
            });

            // Modificar la tabla personals
            Schema::table('personals', function (Blueprint $table) {
                // Hacer que el campo nro_documento sea único
                $table->unique('nro_documento');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recursos_and_personals_tables', function (Blueprint $table) {
            // Revertir los cambios en la tabla recursos
            Schema::table('recursos', function (Blueprint $table) {
                // Eliminar la restricción única en nro_serie
                $table->dropUnique(['nro_serie']);
            });

            // Revertir los cambios en la tabla personals
            Schema::table('personals', function (Blueprint $table) {
                // Eliminar la restricción única en nro_documento
                $table->dropUnique(['nro_documento']);
            });
        });
    }
};
