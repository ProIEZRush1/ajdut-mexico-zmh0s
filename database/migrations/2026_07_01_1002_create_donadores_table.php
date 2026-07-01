<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('rfc')->nullable();
            $table->string('razon_social')->nullable();
            $table->text('direccion_fiscal')->nullable();
            $table->string('plan_actual')->nullable();
            $table->string('estado')->default('activo'); // activo, inactivo, cancelado
            $table->string('stripe_customer_id')->nullable();
            $table->date('fecha_primer_donacion')->nullable();
            $table->decimal('total_donado', 12, 2)->default(0);
            $table->string('idioma')->default('es');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donadores');
    }
};
