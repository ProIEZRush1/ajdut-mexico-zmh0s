<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaNoticias;
use App\Models\Noticia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class NoticiasController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Noticia::with('categoria');

        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($request->filled('publicada')) {
            $query->where('publicada', $request->publicada === '1');
        }

        return Inertia::render('Admin/Noticias/Index', [
            'noticias' => $query->orderByDesc('created_at')->paginate(15)->withQueryString(),
            'categorias' => CategoriaNoticias::orderBy('nombre')->get(),
            'filters' => $request->only(['q', 'categoria_id', 'publicada']),
            'flash' => ['success' => session('success'), 'error' => session('error')],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Noticias/Create', [
            'categorias' => CategoriaNoticias::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'categoria_id' => ['nullable', 'exists:categorias_noticias,id'],
            'titulo' => ['required', 'string', 'max:300'],
            'resumen' => ['required', 'string', 'max:500'],
            'contenido' => ['required', 'string'],
            'autor' => ['nullable', 'string', 'max:100'],
            'publicada' => ['boolean'],
            'fecha_publicacion' => ['nullable', 'date'],
        ]);

        $data['slug'] = Str::slug($data['titulo']);

        Noticia::create($data);

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia creada correctamente.');
    }

    public function edit(Noticia $noticia): Response
    {
        return Inertia::render('Admin/Noticias/Edit', [
            'noticia' => $noticia,
            'categorias' => CategoriaNoticias::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Noticia $noticia): RedirectResponse
    {
        $data = $request->validate([
            'categoria_id' => ['nullable', 'exists:categorias_noticias,id'],
            'titulo' => ['required', 'string', 'max:300'],
            'resumen' => ['required', 'string', 'max:500'],
            'contenido' => ['required', 'string'],
            'autor' => ['nullable', 'string', 'max:100'],
            'publicada' => ['boolean'],
            'fecha_publicacion' => ['nullable', 'date'],
        ]);

        $noticia->update($data);

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia actualizada correctamente.');
    }

    public function destroy(Noticia $noticia): RedirectResponse
    {
        $noticia->delete();
        return back()->with('success', 'Noticia eliminada correctamente.');
    }
}
