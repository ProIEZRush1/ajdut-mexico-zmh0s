<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonio extends Model
{
    protected $table = 'testimonios';

    protected $fillable = [
        'nombre', 'cargo', 'organizacion', 'testimonio',
        'foto', 'estrellas', 'activo', 'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'estrellas' => 'integer',
        'orden' => 'integer',
    ];
}
