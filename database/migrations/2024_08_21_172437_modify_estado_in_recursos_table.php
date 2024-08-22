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
        Schema::table('recursos', function (Blueprint $table) {
            // Modificando el campo 'estado'
            $table->tinyInteger('estado')->default(1)->comment('1: Disponible, 2: Prestado, 3: En mantenimiento, 4: Dañado')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->tinyInteger('estado')->default(1)->comment(null) ->change();
        });
    }
};
