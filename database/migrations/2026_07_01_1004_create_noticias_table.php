<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_noticias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('color')->default('#0d9488');
            $table->timestamps();
        });

        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_noticias')->nullOnDelete();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('resumen');
            $table->longText('contenido');
            $table->string('imagen_portada')->nullable();
            $table->string('autor')->nullable();
            $table->boolean('publicada')->default(false);
            $table->timestamp('fecha_publicacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('noticias');
        Schema::dropIfExists('categorias_noticias');
    }
};
