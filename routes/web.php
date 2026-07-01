<?php

use App\Http\Controllers\Admin\CausasController;
use App\Http\Controllers\Admin\DonacionesController;
use App\Http\Controllers\Admin\DonadoresController;
use App\Http\Controllers\Admin\EquipoController;
use App\Http\Controllers\Admin\MensajesController;
use App\Http\Controllers\Admin\NoticiasController;
use App\Http\Controllers\Admin\PlanesController;
use App\Http\Controllers\Admin\TestimoniosController;
use App\Http\Controllers\Admin\TransparenciaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/health', function () {
    try {
        $users = \App\Models\User::count();
        return response()->json(['ok' => true, 'db' => 'up', 'users' => $users]);
    } catch (\Throwable $e) {
        // Always return 200 so Coolify/proxy health checks pass even while
        // PostgreSQL is still starting up. The 'db' field signals readiness.
        return response()->json(['ok' => true, 'db' => 'starting', 'error' => $e->getMessage()]);
    }
});

// Public pages (no auth required)
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

Route::get('/donar', [DonarController::class, 'index'])->name('donar.index');
Route::post('/donar/checkout', [DonarController::class, 'checkout'])->name('donar.checkout');
Route::get('/donar/exito', [DonarController::class, 'exito'])->name('donar.exito');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin: Causas
    Route::prefix('admin/causas')->name('admin.causas.')->group(function () {
        Route::get('/', [CausasController::class, 'index'])->name('index');
        Route::get('/crear', [CausasController::class, 'create'])->name('create');
        Route::post('/', [CausasController::class, 'store'])->name('store');
        Route::get('/{causa}/editar', [CausasController::class, 'edit'])->name('edit');
        Route::put('/{causa}', [CausasController::class, 'update'])->name('update');
        Route::delete('/{causa}', [CausasController::class, 'destroy'])->name('destroy');
    });

    // Admin: Planes de donación
    Route::prefix('admin/planes')->name('admin.planes.')->group(function () {
        Route::get('/', [PlanesController::class, 'index'])->name('index');
        Route::get('/crear', [PlanesController::class, 'create'])->name('create');
        Route::post('/', [PlanesController::class, 'store'])->name('store');
        Route::get('/{plane}/editar', [PlanesController::class, 'edit'])->name('edit');
        Route::put('/{plane}', [PlanesController::class, 'update'])->name('update');
        Route::delete('/{plane}', [PlanesController::class, 'destroy'])->name('destroy');
    });

    // Admin: Donadores
    Route::prefix('admin/donadores')->name('admin.donadores.')->group(function () {
        Route::get('/', [DonadoresController::class, 'index'])->name('index');
        Route::post('/', [DonadoresController::class, 'store'])->name('store');
        Route::get('/export-csv', [DonadoresController::class, 'exportCsv'])->name('export');
        Route::get('/{donador}', [DonadoresController::class, 'show'])->name('show');
        Route::put('/{donador}', [DonadoresController::class, 'update'])->name('update');
        Route::delete('/{donador}', [DonadoresController::class, 'destroy'])->name('destroy');
    });

    // Admin: Donaciones
    Route::prefix('admin/donaciones')->name('admin.donaciones.')->group(function () {
        Route::get('/', [DonacionesController::class, 'index'])->name('index');
        Route::get('/crear', [DonacionesController::class, 'create'])->name('create');
        Route::post('/', [DonacionesController::class, 'store'])->name('store');
        Route::delete('/{donacion}', [DonacionesController::class, 'destroy'])->name('destroy');
        Route::post('/{donacion}/recibo', [DonacionesController::class, 'emitirRecibo'])->name('recibo');
    });

    // Admin: Noticias
    Route::prefix('admin/noticias')->name('admin.noticias.')->group(function () {
        Route::get('/', [NoticiasController::class, 'index'])->name('index');
        Route::get('/crear', [NoticiasController::class, 'create'])->name('create');
        Route::post('/', [NoticiasController::class, 'store'])->name('store');
        Route::get('/{noticia}/editar', [NoticiasController::class, 'edit'])->name('edit');
        Route::put('/{noticia}', [NoticiasController::class, 'update'])->name('update');
        Route::delete('/{noticia}', [NoticiasController::class, 'destroy'])->name('destroy');
    });

    // Admin: Transparencia
    Route::prefix('admin/transparencia')->name('admin.transparencia.')->group(function () {
        Route::get('/', [TransparenciaController::class, 'index'])->name('index');
        Route::post('/', [TransparenciaController::class, 'store'])->name('store');
        Route::put('/{transparencium}', [TransparenciaController::class, 'update'])->name('update');
        Route::delete('/{transparencium}', [TransparenciaController::class, 'destroy'])->name('destroy');
    });

    // Admin: Mensajes de contacto
    Route::prefix('admin/mensajes')->name('admin.mensajes.')->group(function () {
        Route::get('/', [MensajesController::class, 'index'])->name('index');
        Route::get('/{mensaje}', [MensajesController::class, 'show'])->name('show');
        Route::put('/{mensaje}', [MensajesController::class, 'update'])->name('update');
        Route::delete('/{mensaje}', [MensajesController::class, 'destroy'])->name('destroy');
    });

    // Admin: Testimonios
    Route::prefix('admin/testimonios')->name('admin.testimonios.')->group(function () {
        Route::get('/', [TestimoniosController::class, 'index'])->name('index');
        Route::post('/', [TestimoniosController::class, 'store'])->name('store');
        Route::put('/{testimonio}', [TestimoniosController::class, 'update'])->name('update');
        Route::delete('/{testimonio}', [TestimoniosController::class, 'destroy'])->name('destroy');
    });

    // Admin: Equipo
    Route::prefix('admin/equipo')->name('admin.equipo.')->group(function () {
        Route::get('/', [EquipoController::class, 'index'])->name('index');
        Route::post('/', [EquipoController::class, 'store'])->name('store');
        Route::put('/{equipo}', [EquipoController::class, 'update'])->name('update');
        Route::delete('/{equipo}', [EquipoController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
