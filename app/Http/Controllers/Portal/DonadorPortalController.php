<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Donacion;
use App\Models\Donador;
use App\Models\ReciboFiscal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DonadorPortalController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $donador = Donador::where('email', $user->email)->first();

        $donaciones = null;
        $recibos = null;
        $suscripcion_activa = null;

        if ($donador) {
            $donaciones = Donacion::with('causa', 'plan')
                ->where('donador_id', $donador->id)
                ->orderByDesc('created_at')
                ->get(['id', 'folio', 'causa_id', 'plan_id', 'monto', 'frecuencia', 'estado', 'fecha_pago', 'created_at']);

            $recibos = ReciboFiscal::where('donador_id', $donador->id)
                ->orderByDesc('created_at')
                ->get(['id', 'folio_cfdi', 'monto', 'fecha_emision', 'archivo_pdf', 'created_at']);

            $suscripcion_activa = Donacion::with('causa', 'plan')
                ->where('donador_id', $donador->id)
                ->where('estado', 'completada')
                ->whereIn('frecuencia', ['mensual', 'anual'])
                ->whereNotNull('stripe_subscription_id')
                ->orderByDesc('created_at')
                ->first();
        }

        return Inertia::render('Portal/Index', [
            'donador' => $donador,
            'donaciones' => $donaciones,
            'recibos' => $recibos,
            'suscripcion_activa' => $suscripcion_activa,
        ]);
    }

    public function updatePerfil(Request $request)
    {
        $user = $request->user();
        $donador = Donador::where('email', $user->email)->firstOrFail();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'rfc' => ['nullable', 'string', 'max:20'],
            'razon_social' => ['nullable', 'string', 'max:200'],
        ]);

        $donador->update($data);

        return back()->with('success', 'Datos actualizados correctamente.');
    }
}
