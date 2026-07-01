<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes_transparencia', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('tipo')->default('anual'); // anual, trimestral, causa
            $table->integer('anio');
            $table->string('trimestre')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('archivo')->nullable();
            $table->decimal('total_recaudado', 12, 2)->default(0);
            $table->decimal('total_gastado', 12, 2)->default(0);
            $table->integer('donadores_activos')->default(0);
            $table->integer('beneficiarios')->default(0);
            $table->boolean('publicado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes_transparencia');
    }
};
