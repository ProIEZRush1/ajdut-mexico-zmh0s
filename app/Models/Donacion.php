<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Donacion extends Model
{
    protected $table = 'donaciones';

    protected $fillable = [
        'folio', 'donador_id', 'causa_id', 'plan_id', 'monto', 'moneda',
        'frecuencia', 'estado', 'metodo_pago', 'stripe_payment_intent_id',
        'stripe_subscription_id', 'fecha_pago', 'recibo_emitido', 'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'recibo_emitido' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $d) {
            if (empty($d->folio)) {
                $d->folio = 'DON-' . strtoupper(Str::random(8));
            }
        });
    }

    public function donador(): BelongsTo
    {
        return $this->belongsTo(Donador::class, 'donador_id');
    }

    public function causa(): BelongsTo
    {
        return $this->belongsTo(Causa::class, 'causa_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanDonacion::class, 'plan_id');
    }

    public function recibo(): HasOne
    {
        return $this->hasOne(ReciboFiscal::class, 'donacion_id');
    }
}
