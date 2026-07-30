<?php

namespace App\Http\Controllers;

use App\Models\Causa;
use App\Models\Donacion;
use App\Models\Donador;
use App\Models\MiembroEquipo;
use App\Models\Noticia;
use App\Models\PlanDonacion;
use App\Models\ReporteTransparencia;
use App\Models\Testimonio;
use Inertia\Inertia;
use Inertia\Response;

class PublicPagesController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home', [
            'causas' => Causa::where('activa', true)
                ->orderByDesc('destacada')
                ->orderByDesc('recaudado')
                ->limit(3)
                ->get(['id', 'titulo', 'descripcion_corta', 'meta_recaudacion', 'recaudado', 'categoria', 'beneficiarios']),
            'planes' => PlanDonacion::where('activo', true)->orderBy('orden')->limit(3)->get(),
            'testimonios' => Testimonio::where('activo', true)->orderByDesc('created_at')->limit(3)->get(),
            'stats' => [
                'donadores' => 154,
                'total_recaudado' => 506680,
                'causas_completadas' => 80,
                'beneficiarios' => '+80',
            ],
        ]);
    }

    public function quienesSomos(): Response
    {
        return Inertia::render('QuienesSomos', [
            'equipo' => MiembroEquipo::where('activo', true)->orderBy('orden')->get(),
        ]);
    }

    public function causas(): Response
    {
        return Inertia::render('CausasPublicas', [
            'causas' => Causa::where('activa', true)
                ->orderByDesc('destacada')
                ->get(['id', 'titulo', 'descripcion_corta', 'meta_recaudacion', 'recaudado', 'categoria', 'beneficiarios', 'ubicacion']),
        ]);
    }

    public function jaguim(): Response
    {
        return Inertia::render('JaguimPublicas', [
            'campanas' => Causa::where('activa', true)
                ->whereNotNull('jag')
                ->orderByDesc('destacada')
                ->orderBy('jag')
                ->get(['id', 'titulo', 'descripcion_corta', 'descripcion', 'imagen', 'meta_recaudacion', 'recaudado', 'jag', 'beneficiarios', 'ubicacion']),
        ]);
    }

    public function planes(): Response
    {
        return Inertia::render('PlanesPublicos', [
            'planes' => PlanDonacion::where('activo', true)->orderBy('orden')->get(),
        ]);
    }

    public function transparencia(): Response
    {
        return Inertia::render('TransparenciaPublica', [
            'reportes' => ReporteTransparencia::where('publicado', true)->orderByDesc('anio')->orderByDesc('created_at')->get(),
            'metricas' => [
                'donadores' => 154,
                'total_recaudado' => 506680,
                'beneficiarios' => '+80',
                'causas_completadas' => 80,
            ],
        ]);
    }

    public function noticias(): Response
    {
        return Inertia::render('NoticiasPublicas', [
            'noticias' => Noticia::with('categoria')
                ->where('publicada', true)
                ->orderByDesc('fecha_publicacion')
                ->get(['id', 'titulo', 'resumen', 'categoria_id', 'autor', 'fecha_publicacion', 'publicada']),
        ]);
    }
}
