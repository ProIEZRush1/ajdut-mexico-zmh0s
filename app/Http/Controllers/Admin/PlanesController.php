<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanDonacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PlanesController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Planes/Index', [
            'planes' => PlanDonacion::orderBy('orden')->get(),
            'flash' => ['success' => session('success'), 'error' => session('error')],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Planes/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'monto_sugerido' => ['nullable', 'numeric', 'min:0'],
            'monto_libre' => ['boolean'],
            'frecuencia' => ['required', 'in:mensual,anual,unica'],
            'beneficios' => ['nullable', 'array'],
            'beneficios.*' => ['string'],
            'color' => ['string', 'max:20'],
            'icono' => ['string', 'max:10'],
            'activo' => ['boolean'],
            'orden' => ['integer'],
        ]);

        $data['slug'] = Str::slug($data['nombre']);

        PlanDonacion::create($data);

        return redirect()->route('admin.planes.index')->with('success', 'Plan creado correctamente.');
    }

    public function edit(PlanDonacion $plane): Response
    {
        return Inertia::render('Admin/Planes/Edit', ['plan' => $plane]);
    }

    public function update(Request $request, PlanDonacion $plane): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'monto_sugerido' => ['nullable', 'numeric', 'min:0'],
            'monto_libre' => ['boolean'],
            'frecuencia' => ['required', 'in:mensual,anual,unica'],
            'beneficios' => ['nullable', 'array'],
            'beneficios.*' => ['string'],
            'color' => ['string', 'max:20'],
            'icono' => ['string', 'max:10'],
            'activo' => ['boolean'],
            'orden' => ['integer'],
        ]);

        $plane->update($data);

        return redirect()->route('admin.planes.index')->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(PlanDonacion $plane): RedirectResponse
    {
        $plane->delete();
        return back()->with('success', 'Plan eliminado correctamente.');
    }
}
