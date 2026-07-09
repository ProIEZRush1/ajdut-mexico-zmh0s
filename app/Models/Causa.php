<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Causa extends Model
{
    protected $table = 'causas';

    protected $fillable = [
        'titulo', 'slug', 'descripcion_corta', 'descripcion', 'imagen',
        'meta_recaudacion', 'recaudado', 'categoria', 'jag', 'activa', 'destacada',
        'fecha_inicio', 'fecha_fin', 'beneficiarios', 'ubicacion',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'destacada' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'meta_recaudacion' => 'decimal:2',
        'recaudado' => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $causa) {
            if (empty($causa->slug)) {
                $causa->slug = Str::slug($causa->titulo);
            }
        });
    }

    public function donaciones(): HasMany
    {
        return $this->hasMany(Donacion::class, 'causa_id');
    }

    public function getPorcentajeAttribute(): float
    {
        if ($this->meta_recaudacion <= 0) return 0;
        return min(100, round(($this->recaudado / $this->meta_recaudacion) * 100, 1));
    }
}
