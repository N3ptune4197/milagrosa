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
        Schema::table('personals', function (Blueprint $table) {
            
            $table->dropForeign(['id_tipodocs']);

            // Luego eliminar el campo id_tipodoc
            $table->dropColumn('id_tipodocs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personals', function (Blueprint $table) {
            //
            
            $table->unsignedBigInteger('id_tipodocs');

            // Restaurar la clave foránea
            $table->foreign('id_tipodocs')->references('id')->on('tipodocs');
        });
    }
};
