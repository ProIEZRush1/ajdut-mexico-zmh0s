<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CategoriaNoticias extends Model
{
    protected $table = 'categorias_noticias';

    protected $fillable = ['nombre', 'slug', 'color'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $c) {
            if (empty($c->slug)) {
                $c->slug = Str::slug($c->nombre);
            }
        });
    }

    public function noticias(): HasMany
    {
        return $this->hasMany(Noticia::class, 'categoria_id');
    }
}
