<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReciboFiscal extends Model
{
    protected $table = 'recibos_fiscales';

    protected $fillable = [
        'folio', 'donacion_id', 'donador_id', 'monto', 'rfc_donador',
        'razon_social', 'concepto', 'fecha_emision', 'archivo', 'estado',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_emision' => 'date',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $r) {
            if (empty($r->folio)) {
                $r->folio = 'REC-' . date('Y') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function donacion(): BelongsTo
    {
        return $this->belongsTo(Donacion::class, 'donacion_id');
    }

    public function donador(): BelongsTo
    {
        return $this->belongsTo(Donador::class, 'donador_id');
    }
}
