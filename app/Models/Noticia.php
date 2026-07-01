<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Noticia extends Model
{
    protected $table = 'noticias';

    protected $fillable = [
        'categoria_id', 'titulo', 'slug', 'resumen', 'contenido',
        'imagen_portada', 'autor', 'publicada', 'fecha_publicacion',
    ];

    protected $casts = [
        'publicada' => 'boolean',
        'fecha_publicacion' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $n) {
            if (empty($n->slug)) {
                $n->slug = Str::slug($n->titulo);
            }
        });
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaNoticias::class, 'categoria_id');
    }
}
