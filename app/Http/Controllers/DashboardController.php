<?php

namespace App\Http\Controllers;

use App\Models\Causa;
use App\Models\Donacion;
use App\Models\Donador;
use App\Models\MensajeContacto;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'donadores' => Donador::where('estado', 'activo')->count(),
                'donaciones_mes' => Donacion::where('estado', 'completada')
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'total_recaudado' => Donacion::where('estado', 'completada')->sum('monto'),
                'causas_activas' => Causa::where('activa', true)->count(),
                'mensajes_nuevos' => MensajeContacto::where('estado', 'nuevo')->count(),
            ],
            'causas_recientes' => Causa::where('activa', true)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(['id', 'titulo', 'meta_recaudacion', 'recaudado', 'categoria']),
            'donaciones_recientes' => Donacion::with('donador')
                ->where('estado', 'completada')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(['id', 'folio', 'donador_id', 'monto', 'frecuencia', 'created_at']),
        ]);
    }
}
