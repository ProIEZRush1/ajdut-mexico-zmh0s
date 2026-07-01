<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Inertia\Inertia;
use Inertia\Response;

class DonadoresController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Donador::withCount('donaciones')->withSum('donaciones', 'monto');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->q . '%')
                  ->orWhere('apellido', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        return Inertia::render('Admin/Donadores/Index', [
            'donadores' => $query->orderByDesc('created_at')->paginate(20)->withQueryString(),
            'filters' => $request->only(['q', 'estado']),
            'flash' => ['success' => session('success'), 'error' => session('error')],
        ]);
    }

    public function show(Donador $donador): Response
    {
        return Inertia::render('Admin/Donadores/Show', [
            'donador' => $donador->load('donaciones.causa', 'donaciones.plan', 'recibos'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:donadores,email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'rfc' => ['nullable', 'string', 'max:20'],
            'razon_social' => ['nullable', 'string', 'max:200'],
            'estado' => ['in:activo,inactivo,cancelado'],
        ]);

        Donador::create($data);

        return redirect()->route('admin.donadores.index')->with('success', 'Donador registrado correctamente.');
    }

    public function update(Request $request, Donador $donador): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:donadores,email,' . $donador->id],
            'telefono' => ['nullable', 'string', 'max:20'],
            'rfc' => ['nullable', 'string', 'max:20'],
            'razon_social' => ['nullable', 'string', 'max:200'],
            'estado' => ['in:activo,inactivo,cancelado'],
        ]);

        $donador->update($data);

        return back()->with('success', 'Donador actualizado correctamente.');
    }

    public function destroy(Donador $donador): RedirectResponse
    {
        $donador->delete();
        return redirect()->route('admin.donadores.index')->with('success', 'Donador eliminado.');
    }

    public function exportCsv(Request $request)
    {
        $donadores = Donador::all();

        $csv = "Nombre,Apellido,Email,Teléfono,RFC,Estado,Total Donado\n";
        foreach ($donadores as $d) {
            $csv .= "\"{$d->nombre}\",\"{$d->apellido}\",\"{$d->email}\",\"{$d->telefono}\",\"{$d->rfc}\",\"{$d->estado}\",\"{$d->total_donado}\"\n";
        }

        return FacadeResponse::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="donadores.csv"',
        ]);
    }
}
