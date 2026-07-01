<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donador extends Model
{
    protected $table = 'donadores';

    protected $fillable = [
        'user_id', 'nombre', 'apellido', 'email', 'telefono',
        'rfc', 'razon_social', 'direccion_fiscal', 'plan_actual',
        'estado', 'stripe_customer_id', 'fecha_primer_donacion',
        'total_donado', 'idioma',
    ];

    protected $casts = [
        'fecha_primer_donacion' => 'date',
        'total_donado' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donaciones(): HasMany
    {
        return $this->hasMany(Donacion::class, 'donador_id');
    }

    public function recibos(): HasMany
    {
        return $this->hasMany(ReciboFiscal::class, 'donador_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
