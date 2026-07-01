<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes_donacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion');
            $table->decimal('monto_sugerido', 10, 2)->nullable();
            $table->boolean('monto_libre')->default(false);
            $table->string('frecuencia')->default('mensual'); // mensual, anual, unica
            $table->json('beneficios')->nullable();
            $table->string('color')->default('#0d9488');
            $table->string('icono')->default('🎗️');
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->string('stripe_price_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes_donacion');
    }
};
