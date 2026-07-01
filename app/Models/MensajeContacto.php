<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensajeContacto extends Model
{
    protected $table = 'mensajes_contacto';

    protected $fillable = [
        'nombre', 'email', 'telefono', 'asunto', 'mensaje',
        'estado', 'respuesta', 'respondido_en',
    ];

    protected $casts = [
        'respondido_en' => 'datetime',
    ];
}
