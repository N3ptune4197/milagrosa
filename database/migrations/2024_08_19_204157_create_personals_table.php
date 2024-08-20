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
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 30);
            $table->string('a_paterno', 30);
            $table->string('a_materno', 30);
            $table->char('telefono', 9);
            $table->foreignId('id_tipodocs')->constrained('tipodocs');
            $table->string('nro_documento', 12);
            $table->string('cargo', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }
};
