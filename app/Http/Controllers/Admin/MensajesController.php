<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MensajeContacto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MensajesController extends Controller
{
    public function index(Request $request): Response
    {
        $query = MensajeContacto::query();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        return Inertia::render('Admin/Mensajes/Index', [
            'mensajes' => $query->orderByDesc('created_at')->paginate(20)->withQueryString(),
            'filters' => $request->only(['estado']),
            'flash' => ['success' => session('success'), 'error' => session('error')],
            'sin_leer' => MensajeContacto::where('estado', 'nuevo')->count(),
        ]);
    }

    public function show(MensajeContacto $mensaje): Response
    {
        if ($mensaje->estado === 'nuevo') {
            $mensaje->update(['estado' => 'leido']);
        }

        return Inertia::render('Admin/Mensajes/Show', ['mensaje' => $mensaje]);
    }

    public function update(Request $request, MensajeContacto $mensaje): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', 'in:nuevo,leido,respondido'],
            'respuesta' => ['nullable', 'string'],
        ]);

        if ($data['estado'] === 'respondido' && !empty($data['respuesta'])) {
            $data['respondido_en'] = now();
        }

        $mensaje->update($data);

        return back()->with('success', 'Mensaje actualizado correctamente.');
    }

    public function destroy(MensajeContacto $mensaje): RedirectResponse
    {
        $mensaje->delete();
        return redirect()->route('admin.mensajes.index')->with('success', 'Mensaje eliminado.');
    }
}
