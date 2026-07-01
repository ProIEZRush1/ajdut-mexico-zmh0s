<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('causas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descripcion_corta');
            $table->longText('descripcion');
            $table->string('imagen')->nullable();
            $table->decimal('meta_recaudacion', 12, 2)->default(0);
            $table->decimal('recaudado', 12, 2)->default(0);
            $table->string('categoria')->default('general');
            $table->boolean('activa')->default(true);
            $table->boolean('destacada')->default(false);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('beneficiarios')->default(0);
            $table->string('ubicacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('causas');
    }
};
