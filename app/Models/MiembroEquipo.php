<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiembroEquipo extends Model
{
    protected $table = 'miembros_equipo';

    protected $fillable = [
        'nombre', 'cargo', 'area', 'biografia', 'foto',
        'email', 'linkedin', 'activo', 'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];
}
