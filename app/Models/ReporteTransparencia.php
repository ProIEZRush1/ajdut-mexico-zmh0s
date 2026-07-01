<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteTransparencia extends Model
{
    protected $table = 'reportes_transparencia';

    protected $fillable = [
        'titulo', 'tipo', 'anio', 'trimestre', 'descripcion', 'archivo',
        'total_recaudado', 'total_gastado', 'donadores_activos',
        'beneficiarios', 'publicado',
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'total_recaudado' => 'decimal:2',
        'total_gastado' => 'decimal:2',
        'anio' => 'integer',
        'donadores_activos' => 'integer',
        'beneficiarios' => 'integer',
    ];
}
