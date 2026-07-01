<?php

namespace App\Http\Controllers;

use App\Models\Causa;
use App\Models\Donacion;
use App\Models\Donador;
use App\Models\PlanDonacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DonarController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Donar/Index', [
            'causas' => Causa::where('activa', true)->orderByDesc('destacada')->get([
                'id', 'titulo', 'descripcion_corta', 'meta_recaudacion', 'recaudado', 'categoria', 'imagen',
            ]),
            'planes' => PlanDonacion::where('activo', true)->orderBy('orden')->get(),
            'causa_preseleccionada' => $request->filled('causa')
                ? Causa::where('activa', true)->find($request->causa, ['id', 'titulo'])
                : null,
            'trial_locked' => config('trial.locked'),
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        if (config('trial.locked')) {
            return back()->with('trial_locked', true);
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'monto' => ['required', 'numeric', 'min:10'],
            'frecuencia' => ['required', 'in:unica,mensual,anual'],
            'causa_id' => ['nullable', 'exists:causas,id'],
            'plan_id' => ['nullable', 'exists:planes_donacion,id'],
            'firma_electronica' => ['nullable', 'string'],
            'firma_fecha' => ['nullable', 'string'],
        ]);

        $donador = Donador::firstOrCreate(
            ['email' => $data['email']],
            [
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'estado' => 'activo',
            ]
        );

        if (!config('app.stripe_key')) {
            $donacion = Donacion::create([
                'donador_id' => $donador->id,
                'causa_id' => $data['causa_id'] ?? null,
                'plan_id' => $data['plan_id'] ?? null,
                'monto' => $data['monto'],
                'frecuencia' => $data['frecuencia'],
                'estado' => 'completada',
                'metodo_pago' => 'demo',
                'fecha_pago' => now(),
                'firma_electronica' => $data['firma_electronica'] ?? null,
                'firma_fecha' => isset($data['firma_fecha']) ? now() : null,
            ]);

            if ($donacion->causa_id) {
                Causa::where('id', $donacion->causa_id)->increment('recaudado', $donacion->monto);
            }

            $donador->increment('total_donado', $donacion->monto);

            return redirect()->route('donar.exito', ['folio' => $donacion->folio]);
        }

        return redirect()->route('donar.index')->with('error', 'Stripe no configurado.');
    }

    public function exito(Request $request): Response
    {
        $donacion = null;
        if ($request->filled('folio')) {
            $donacion = Donacion::with('donador', 'causa')
                ->where('folio', $request->folio)
                ->first();
        }

        return Inertia::render('Donar/Exito', ['donacion' => $donacion]);
    }
}
