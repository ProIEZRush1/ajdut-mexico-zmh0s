<?php

namespace App\Http\Controllers;

use App\Models\MensajeContacto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contacto');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'asunto' => ['required', 'string', 'max:200'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ]);

        MensajeContacto::create($data);

        return back()->with('success', 'Tu mensaje fue enviado. Te contactaremos pronto.');
    }
}
