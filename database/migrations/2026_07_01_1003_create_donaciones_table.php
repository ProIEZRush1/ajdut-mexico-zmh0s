<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donaciones', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->foreignId('donador_id')->nullable()->constrained('donadores')->nullOnDelete();
            $table->foreignId('causa_id')->nullable()->constrained('causas')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('planes_donacion')->nullOnDelete();
            $table->decimal('monto', 10, 2);
            $table->string('moneda')->default('MXN');
            $table->string('frecuencia')->default('unica'); // unica, mensual, anual
            $table->string('estado')->default('pendiente'); // pendiente, completada, fallida, reembolsada
            $table->string('metodo_pago')->nullable(); // stripe, transferencia, efectivo
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->boolean('recibo_emitido')->default(false);
            $table->string('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donaciones');
    }
};
