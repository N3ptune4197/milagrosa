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
        Schema::dropIfExists('tipodocs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tipodocs', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 50);
            $table->string('abreviatura', 8);
            $table->timestamps();
        });
    }
};
