<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReporteTransparencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TransparenciaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Transparencia/Index', [
            'reportes' => ReporteTransparencia::orderByDesc('anio')->orderByDesc('created_at')->get(),
            'flash' => ['success' => session('success'), 'error' => session('error')],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:200'],
            'tipo' => ['required', 'in:anual,trimestral,causa'],
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'trimestre' => ['nullable', 'string', 'max:10'],
            'descripcion' => ['nullable', 'string'],
            'total_recaudado' => ['numeric', 'min:0'],
            'total_gastado' => ['numeric', 'min:0'],
            'donadores_activos' => ['integer', 'min:0'],
            'beneficiarios' => ['integer', 'min:0'],
            'publicado' => ['boolean'],
        ]);

        ReporteTransparencia::create($data);

        return back()->with('success', 'Reporte de transparencia creado correctamente.');
    }

    public function update(Request $request, ReporteTransparencia $transparencium): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:200'],
            'tipo' => ['required', 'in:anual,trimestral,causa'],
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'trimestre' => ['nullable', 'string', 'max:10'],
            'descripcion' => ['nullable', 'string'],
            'total_recaudado' => ['numeric', 'min:0'],
            'total_gastado' => ['numeric', 'min:0'],
            'donadores_activos' => ['integer', 'min:0'],
            'beneficiarios' => ['integer', 'min:0'],
            'publicado' => ['boolean'],
        ]);

        $transparencium->update($data);

        return back()->with('success', 'Reporte actualizado correctamente.');
    }

    public function destroy(ReporteTransparencia $transparencium): RedirectResponse
    {
        $transparencium->delete();
        return back()->with('success', 'Reporte eliminado.');
    }
}
