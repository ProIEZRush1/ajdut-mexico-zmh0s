<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PlanDonacion extends Model
{
    protected $table = 'planes_donacion';

    protected $fillable = [
        'nombre', 'slug', 'descripcion', 'monto_sugerido', 'monto_libre',
        'frecuencia', 'beneficios', 'color', 'icono', 'activo', 'orden',
        'stripe_price_id',
    ];

    protected $casts = [
        'beneficios' => 'array',
        'monto_libre' => 'boolean',
        'activo' => 'boolean',
        'monto_sugerido' => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->nombre);
            }
        });
    }

    public function donaciones(): HasMany
    {
        return $this->hasMany(Donacion::class, 'plan_id');
    }
}
