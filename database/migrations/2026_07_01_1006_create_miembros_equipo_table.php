<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('miembros_equipo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cargo');
            $table->string('area')->nullable();
            $table->text('biografia')->nullable();
            $table->string('foto')->nullable();
            $table->string('email')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('miembros_equipo');
    }
};
