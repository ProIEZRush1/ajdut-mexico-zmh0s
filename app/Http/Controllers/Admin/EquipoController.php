<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiembroEquipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Equipo/Index', [
            'miembros' => MiembroEquipo::orderBy('orden')->get(),
            'flash' => ['success' => session('success'), 'error' => session('error')],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'cargo' => ['required', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'biografia' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:150'],
            'linkedin' => ['nullable', 'string', 'max:200'],
            'activo' => ['boolean'],
            'orden' => ['integer', 'min:0'],
        ]);

        MiembroEquipo::create($data);

        return back()->with('success', 'Miembro del equipo agregado correctamente.');
    }

    public function update(Request $request, MiembroEquipo $equipo): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'cargo' => ['required', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'biografia' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:150'],
            'linkedin' => ['nullable', 'string', 'max:200'],
            'activo' => ['boolean'],
            'orden' => ['integer', 'min:0'],
        ]);

        $equipo->update($data);

        return back()->with('success', 'Miembro del equipo actualizado correctamente.');
    }

    public function destroy(MiembroEquipo $equipo): RedirectResponse
    {
        $equipo->delete();
        return back()->with('success', 'Miembro eliminado del equipo.');
    }
}
