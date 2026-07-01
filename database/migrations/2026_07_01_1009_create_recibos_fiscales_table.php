<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recibos_fiscales', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->foreignId('donacion_id')->constrained('donaciones')->cascadeOnDelete();
            $table->foreignId('donador_id')->constrained('donadores')->cascadeOnDelete();
            $table->decimal('monto', 10, 2);
            $table->string('rfc_donador')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('concepto');
            $table->date('fecha_emision');
            $table->string('archivo')->nullable();
            $table->string('estado')->default('emitido'); // emitido, cancelado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recibos_fiscales');
    }
};
