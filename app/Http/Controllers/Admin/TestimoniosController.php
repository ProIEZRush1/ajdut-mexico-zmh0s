<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TestimoniosController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Testimonios/Index', [
            'testimonios' => Testimonio::orderBy('orden')->get(),
            'flash' => ['success' => session('success'), 'error' => session('error')],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'cargo' => ['nullable', 'string', 'max:100'],
            'organizacion' => ['nullable', 'string', 'max:150'],
            'testimonio' => ['required', 'string'],
            'estrellas' => ['integer', 'min:1', 'max:5'],
            'activo' => ['boolean'],
            'orden' => ['integer', 'min:0'],
        ]);

        Testimonio::create($data);

        return back()->with('success', 'Testimonio agregado correctamente.');
    }

    public function update(Request $request, Testimonio $testimonio): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'cargo' => ['nullable', 'string', 'max:100'],
            'organizacion' => ['nullable', 'string', 'max:150'],
            'testimonio' => ['required', 'string'],
            'estrellas' => ['integer', 'min:1', 'max:5'],
            'activo' => ['boolean'],
            'orden' => ['integer', 'min:0'],
        ]);

        $testimonio->update($data);

        return back()->with('success', 'Testimonio actualizado correctamente.');
    }

    public function destroy(Testimonio $testimonio): RedirectResponse
    {
        $testimonio->delete();
        return back()->with('success', 'Testimonio eliminado.');
    }
}
