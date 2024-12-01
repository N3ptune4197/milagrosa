<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->string('fuente_adquisicion')->change(); // Cambiar a VARCHAR
        });
    }

    public function down()
    {
        Schema::table('recursos', function (Blueprint $table) {
            // Cambiar de nuevo a ENUM si es necesario
            $table->enum('fuente_adquisicion', ['donacion externa', 'donacion interna', 'especificar'])->default('especificar')->change();
        });
    }
};
