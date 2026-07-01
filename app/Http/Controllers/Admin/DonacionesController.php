<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Causa;
use App\Models\Donacion;
use App\Models\Donador;
use App\Models\PlanDonacion;
use App\Models\ReciboFiscal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DonacionesController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Donacion::with('donador', 'causa', 'plan');

        if ($request->filled('q')) {
            $query->where('folio', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        return Inertia::render('Admin/Donaciones/Index', [
            'donaciones' => $query->orderByDesc('created_at')->paginate(20)->withQueryString(),
            'filters' => $request->only(['q', 'estado']),
            'flash' => ['success' => session('success'), 'error' => session('error')],
            'totales' => [
                'total' => Donacion::where('estado', 'completada')->sum('monto'),
                'count' => Donacion::where('estado', 'completada')->count(),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Donaciones/Create', [
            'donadores' => Donador::orderBy('nombre')->get(['id', 'nombre', 'apellido', 'email']),
            'causas' => Causa::where('activa', true)->orderBy('titulo')->get(['id', 'titulo']),
            'planes' => PlanDonacion::where('activo', true)->orderBy('orden')->get(['id', 'nombre', 'monto_sugerido', 'frecuencia']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'donador_id' => ['nullable', 'exists:donadores,id'],
            'causa_id' => ['nullable', 'exists:causas,id'],
            'plan_id' => ['nullable', 'exists:planes_donacion,id'],
            'monto' => ['required', 'numeric', 'min:1'],
            'frecuencia' => ['required', 'in:unica,mensual,anual'],
            'estado' => ['required', 'in:pendiente,completada,fallida'],
            'metodo_pago' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
        ]);

        $data['folio'] = 'DON-' . strtoupper(Str::random(8));

        $donacion = Donacion::create($data);

        if ($donacion->estado === 'completada' && $donacion->causa_id) {
            Causa::where('id', $donacion->causa_id)->increment('recaudado', $donacion->monto);
        }

        if ($donacion->donador_id && $donacion->estado === 'completada') {
            $donador = Donador::find($donacion->donador_id);
            $donador->increment('total_donado', $donacion->monto);
        }

        return redirect()->route('admin.donaciones.index')->with('success', 'Donación registrada: ' . $data['folio']);
    }

    public function destroy(Donacion $donacion): RedirectResponse
    {
        $donacion->delete();
        return back()->with('success', 'Donación eliminada.');
    }

    public function emitirRecibo(Donacion $donacion): RedirectResponse
    {
        if ($donacion->recibo_emitido) {
            return back()->with('error', 'Esta donación ya tiene recibo emitido.');
        }

        $donador = $donacion->donador;

        ReciboFiscal::create([
            'donacion_id' => $donacion->id,
            'donador_id' => $donacion->donador_id,
            'monto' => $donacion->monto,
            'rfc_donador' => $donador?->rfc,
            'razon_social' => $donador?->razon_social ?? ($donador?->nombre . ' ' . $donador?->apellido),
            'concepto' => 'Donación ' . ($donacion->causa?->titulo ?? 'General'),
            'fecha_emision' => now()->toDateString(),
        ]);

        $donacion->update(['recibo_emitido' => true]);

        return back()->with('success', 'Recibo fiscal emitido correctamente.');
    }
}
