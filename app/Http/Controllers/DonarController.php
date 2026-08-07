<?php

namespace App\Http\Controllers;

use App\Models\Causa;
use App\Models\Donacion;
use App\Models\Donador;
use App\Models\PlanDonacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

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
            'monto_preseleccionado' => $request->filled('monto') && is_numeric($request->monto)
                ? (int) $request->monto
                : null,
            'frecuencia_preseleccionada' => in_array($request->freq, ['unica', 'mensual', 'trimestral', 'anual'], true)
                ? $request->freq
                : null,
            'trial_locked' => config('trial.locked'),
        ]);
    }

    public function checkout(Request $request): HttpResponse
    {
        if (config('trial.locked')) {
            return back()->with('trial_locked', true);
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'monto' => ['required', 'numeric', 'min:10'],
            'frecuencia' => ['required', 'in:unica,mensual,trimestral,anual'],
            'causa_id' => ['nullable', 'exists:causas,id'],
            'plan_id' => ['nullable', 'exists:planes_donacion,id'],
            'firma_electronica' => ['required', 'string'],
            'firma_nombre' => ['required', 'string', 'max:150'],
            'firma_fecha' => ['required', 'string'],
        ]);

        $donador = Donador::firstOrCreate(
            ['email' => $data['email']],
            [
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'estado' => 'activo',
            ]
        );

        // Sin llave de Stripe no podemos cobrar de verdad: no fingimos el cobro.
        if (! config('pagos.secret')) {
            return back()->with('error', 'Los pagos en línea están en configuración. Intenta de nuevo en un momento.');
        }

        $causa = ! empty($data['causa_id']) ? Causa::find($data['causa_id']) : null;

        // Se crea la donación como PENDIENTE; se marca completada al confirmar el pago.
        $donacion = Donacion::create([
            'donador_id' => $donador->id,
            'causa_id' => $data['causa_id'] ?? null,
            'plan_id' => $data['plan_id'] ?? null,
            'monto' => $data['monto'],
            'moneda' => strtoupper(config('pagos.currency', 'mxn')),
            'frecuencia' => $data['frecuencia'],
            'estado' => 'pendiente',
            'metodo_pago' => 'stripe',
            'firma_electronica' => $data['firma_electronica'],
            'firma_nombre' => $data['firma_nombre'],
            'firma_fecha' => now(),
        ]);

        $esRecurrente = $data['frecuencia'] !== 'unica';
        $recurring = match ($data['frecuencia']) {
            'mensual' => ['interval' => 'month', 'interval_count' => 1],
            'trimestral' => ['interval' => 'month', 'interval_count' => 3],
            'anual' => ['interval' => 'year', 'interval_count' => 1],
            default => null,
        };

        $priceData = [
            'currency' => config('pagos.currency', 'mxn'),
            'product_data' => [
                'name' => 'Donativo a AJDUT México' . ($causa ? ' · ' . $causa->titulo : ''),
            ],
            'unit_amount' => (int) round(((float) $data['monto']) * 100),
        ];
        if ($recurring) {
            $priceData['recurring'] = $recurring;
        }

        try {
            \Stripe\Stripe::setApiKey(config('pagos.secret'));

            $params = [
                'mode' => $esRecurrente ? 'subscription' : 'payment',
                'customer_email' => $data['email'],
                'line_items' => [[
                    'price_data' => $priceData,
                    'quantity' => 1,
                ]],
                'success_url' => route('donar.exito', ['folio' => $donacion->folio]) . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('donar.index'),
                'locale' => 'es',
                'metadata' => [
                    'folio' => $donacion->folio,
                    'donacion_id' => (string) $donacion->id,
                ],
            ];
            if ($esRecurrente) {
                $params['subscription_data'] = ['metadata' => ['folio' => $donacion->folio]];
            } else {
                $params['payment_intent_data'] = ['metadata' => ['folio' => $donacion->folio]];
            }

            $session = \Stripe\Checkout\Session::create($params);

            $donacion->update(['notas' => 'checkout:' . $session->id]);

            return Inertia::location($session->url);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            $donacion->update(['estado' => 'fallida']);

            return back()->with('error', 'No pudimos iniciar el pago. Por favor intenta de nuevo.');
        }
    }

    public function exito(Request $request): Response
    {
        $donacion = null;
        if ($request->filled('folio')) {
            $donacion = Donacion::with('donador', 'causa')
                ->where('folio', $request->folio)
                ->first();
        }

        // Verificación al regresar de Stripe.
        if ($donacion && $donacion->estado !== 'completada' && $request->filled('session_id') && config('pagos.secret')) {
            try {
                \Stripe\Stripe::setApiKey(config('pagos.secret'));
                $session = \Stripe\Checkout\Session::retrieve($request->session_id);
                if ($session && ($session->status === 'complete'
                    || in_array($session->payment_status, ['paid', 'no_payment_required'], true))) {
                    $this->marcarCompletada($donacion, $session);
                }
            } catch (\Throwable $e) {
                Log::warning('Stripe exito verify error: ' . $e->getMessage());
            }
        }

        return Inertia::render('Donar/Exito', ['donacion' => $donacion?->fresh(['donador', 'causa'])]);
    }

    public function webhook(Request $request): HttpResponse
    {
        if (! config('pagos.secret')) {
            return response('', HttpResponse::HTTP_OK);
        }

        \Stripe\Stripe::setApiKey(config('pagos.secret'));

        $payload = $request->getContent();
        $whsec = config('pagos.webhook_secret');

        try {
            if ($whsec) {
                $event = \Stripe\Webhook::constructEvent($payload, (string) $request->header('Stripe-Signature'), $whsec);
                $event = json_decode(json_encode($event));
            } else {
                $event = json_decode($payload);
            }
        } catch (\Throwable $e) {
            return response('invalid', HttpResponse::HTTP_BAD_REQUEST);
        }

        $type = $event->type ?? null;
        if (in_array($type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
            $sessionId = $event->data->object->id ?? null;
            if ($sessionId) {
                try {
                    // Re-consultar a Stripe = confirma autenticidad aun sin webhook secret.
                    $session = \Stripe\Checkout\Session::retrieve($sessionId);
                    $folio = $session->metadata->folio ?? null;
                    if ($folio && $session->status === 'complete') {
                        $donacion = Donacion::where('folio', $folio)->first();
                        if ($donacion) {
                            $this->marcarCompletada($donacion, $session);
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Stripe webhook verify error: ' . $e->getMessage());
                }
            }
        }

        return response('', HttpResponse::HTTP_OK);
    }

    private function marcarCompletada(Donacion $donacion, $session): void
    {
        if ($donacion->estado === 'completada') {
            return;
        }

        $donacion->update([
            'estado' => 'completada',
            'fecha_pago' => now(),
            'stripe_payment_intent_id' => $session->payment_intent ?? $donacion->stripe_payment_intent_id,
            'stripe_subscription_id' => $session->subscription ?? null,
        ]);

        if ($donacion->causa_id) {
            Causa::where('id', $donacion->causa_id)->increment('recaudado', $donacion->monto);
        }

        if ($donacion->donador) {
            $donacion->donador->increment('total_donado', $donacion->monto);
        }
    }

    public function carta(string $folio)
    {
        $donacion = Donacion::with('donador')->where('folio', $folio)->firstOrFail();

        $frecuencias = ['unica' => 'única vez', 'mensual' => 'mensual', 'trimestral' => 'cada 3 meses', 'anual' => 'anual'];
        $fechaBase = $donacion->firma_fecha ?? $donacion->fecha_pago ?? $donacion->created_at ?? now();

        $logoPath = public_path('logo-ajdut.jpg');
        $logo = is_file($logoPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('carta-autorizacion', [
            'logo' => $logo,
            'folio' => $donacion->folio,
            'fecha' => $fechaBase->locale('es')->isoFormat('D [de] MMMM [de] Y'),
            'monto' => '$' . number_format((float) $donacion->monto, 2) . ' MXN',
            'frecuencia' => $frecuencias[$donacion->frecuencia] ?? $donacion->frecuencia,
            'firmante' => $donacion->firma_nombre
                ?: trim(($donacion->donador->nombre ?? '') . ' ' . ($donacion->donador->apellido ?? '')),
            'firma_img' => $donacion->firma_electronica,
            'donador_email' => $donacion->donador->email ?? null,
        ])->setPaper('letter');

        return $pdf->stream('carta-autorizacion-' . $donacion->folio . '.pdf');
    }
}
