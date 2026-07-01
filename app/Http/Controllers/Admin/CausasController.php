<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Causa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CausasController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Causa::query();
        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('activa')) {
            $query->where('activa', $request->activa === '1');
        }

        return Inertia::render('Admin/Causas/Index', [
            'causas' => $query->orderByDesc('created_at')->paginate(15)->withQueryString(),
            'filters' => $request->only(['q', 'activa']),
            'flash' => ['success' => session('success'), 'error' => session('error')],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Causas/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:200'],
            'descripcion_corta' => ['required', 'string', 'max:500'],
            'descripcion' => ['required', 'string'],
            'meta_recaudacion' => ['required', 'numeric', 'min:0'],
            'categoria' => ['required', 'string', 'max:100'],
            'activa' => ['boolean'],
            'destacada' => ['boolean'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date'],
            'beneficiarios' => ['integer', 'min:0'],
            'ubicacion' => ['nullable', 'string', 'max:200'],
        ]);

        $data['slug'] = Str::slug($data['titulo']);

        Causa::create($data);

        return redirect()->route('admin.causas.index')->with('success', 'Causa creada correctamente.');
    }

    public function edit(Causa $causa): Response
    {
        return Inertia::render('Admin/Causas/Edit', ['causa' => $causa]);
    }

    public function update(Request $request, Causa $causa): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:200'],
            'descripcion_corta' => ['required', 'string', 'max:500'],
            'descripcion' => ['required', 'string'],
            'meta_recaudacion' => ['required', 'numeric', 'min:0'],
            'recaudado' => ['numeric', 'min:0'],
            'categoria' => ['required', 'string', 'max:100'],
            'activa' => ['boolean'],
            'destacada' => ['boolean'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date'],
            'beneficiarios' => ['integer', 'min:0'],
            'ubicacion' => ['nullable', 'string', 'max:200'],
        ]);

        $causa->update($data);

        return redirect()->route('admin.causas.index')->with('success', 'Causa actualizada correctamente.');
    }

    public function destroy(Causa $causa): RedirectResponse
    {
        $causa->delete();
        return back()->with('success', 'Causa eliminada correctamente.');
    }
}
