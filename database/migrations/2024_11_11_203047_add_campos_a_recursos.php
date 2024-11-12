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
            $table->string('modelo')->nullable();
            $table->enum('fuente_adquisicion', ['donacion externa', 'donacion interna', 'especificar'])->default('especificar');
            $table->enum('estado_conservacion', ['sin reparacion', 'con reparacion', 'opera defecto', 'no opera'])->default('sin reparacion');
            $table->text('observacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->dropColumn(['modelo', 'fuente_adquisicion', 'estado_conservacion', 'observacion']);
        });
    }
};
