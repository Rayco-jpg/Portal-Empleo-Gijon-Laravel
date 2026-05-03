<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\PostulacionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PremiumController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->tipo_usuario == 'admin') {
            return redirect()->route('admin.index');
        }
        if ($user->tipo_usuario == 'empresa') {
            return redirect()->route('ofertas.index');
        }
        return redirect()->route('buscador');
    }
    return view('welcome');
})->name('inicio');

Route::controller(AuthController::class)->group(function () {
    Route::get('/registro', 'showRegistro')->name('register');
    Route::post('/registro', 'registrar')->name('registrar');
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('autenticar');
    Route::get('/logout', 'logout')->name('logout');

    Route::get('/recuperar-password', 'showOlvido')->name('password.request');
    Route::post('/recuperar-password', 'enviarEnlace')->name('password.enviar');
    Route::get('/restablecer-password/{token}', 'showRestablecer')->name('password.reset');
    Route::post('/actualizar-password', 'updatePassword')->name('password.update');
});

Route::get('/buscador', [OfertaController::class, 'index'])->name('buscador');
Route::view('/cookies', 'cookies')->name('cookies');
Route::view('/privacidad', 'privacidad')->name('privacidad');
Route::view('/accesibilidad', 'accesibilidad')->name('accesibilidad');
Route::get('/oferta/{id}', [OfertaController::class, 'show'])->name('ofertas.show');
Route::get('/empresa/detalle/{id}', [PerfilController::class, 'verPerfilEmpresa'])->name('perfil.empresa.publico');
Route::post('/reportar-oferta', [ContactoController::class, 'store'])->name('reportar.oferta');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- PAGOS Y PREMIUM ---
    Route::controller(PagoController::class)->group(function () {
        Route::get('/checkout-premium', 'checkout')->name('checkout');
        Route::get('/pago-exito', 'exito')->name('pago.exito');
        Route::get('/pago-cancelado', 'cancelado')->name('pago.cancelado');
        Route::post('/premium/cancelar', 'cancelarSuscripcion')->name('premium.cancelar');
    });

    Route::controller(PremiumController::class)->group(function () {
        Route::get('/premium', 'index')->name('premium.index');
        Route::get('/premium/facturacion', 'facturacion')->name('premium.facturacion');
    });

    // --- CONTACTO ---
    Route::get('/contacto', function () {
        return view('contacto');
    })->name('contacto');
    Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

    // --- GESTIÓN DE PERFIL GENERAL ---
    Route::controller(PerfilController::class)->group(function () {
        Route::get('/perfil', 'show')->name('perfil');
        Route::get('/perfil/editar', 'edit')->name('perfil.edit');
        Route::put('/perfil/actualizar', 'updatePerfil')->name('perfil.update');
        Route::post('/perfil/alerta', 'guardarAlerta')->name('alertas.guardar');
        Route::get('/mis-visitas', 'verVisitas')->name('candidato.visitas');
    });

    // --- RUTAS DE CANDIDATO ---
    // Favoritos
    Route::post('/favoritos/toggle/{id}', [FavoritoController::class, 'toggle'])->name('favoritos.toggle');
    Route::get('/mis-favoritos', [FavoritoController::class, 'index'])->name('favoritos.index');
    
    // Inscripciones
    Route::get('/mis-inscripciones', [PostulacionController::class, 'index'])->name('inscripciones.index');
    Route::post('/postular', [PostulacionController::class, 'store'])->name('inscripciones.postular');
    Route::post('/cancelar-postulacion', [PostulacionController::class, 'destroy'])->name('inscripciones.destroy');

    // --- RUTAS DE EMPRESA ---
    Route::prefix('empresa')->group(function () {
        Route::get('/mis-ofertas', [OfertaController::class, 'misOfertas'])->name('ofertas.index');
        Route::get('/crear-oferta', [OfertaController::class, 'create'])->name('ofertas.create');
        Route::post('/guardar-oferta', [OfertaController::class, 'store'])->name('ofertas.store');
        Route::get('/perfil-candidato/{id}', [PerfilController::class, 'verPerfilPublico'])->name('perfil.candidato');
        Route::get('/oferta/{id}/candidatos', [OfertaController::class, 'verCandidatos'])->name('ofertas.candidatos');
        Route::get('/candidatos-totales', [OfertaController::class, 'candidatosTotales'])->name('empresa.todos-los-candidatos');
        Route::post('/candidato/actualizar-estado', [PostulacionController::class, 'actualizarEstado'])->name('inscripciones.actualizar_estado');
        Route::delete('/oferta/{id}', [OfertaController::class, 'destroy'])->name('ofertas.destroy');
    });

    // --- RUTAS DE ADMINISTRADOR ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/mensajes', [ContactoController::class, 'index'])->name('admin.mensajes');
        Route::patch('/mensajes/{id}/leido', [ContactoController::class, 'marcarLeido'])->name('admin.mensajes.leido');
        
        Route::controller(AdminController::class)->group(function () {
            Route::get('/usuarios', 'usuarios')->name('admin.usuarios');
            Route::delete('/usuarios/{id}', 'destroyUsuario')->name('admin.usuarios.destroy');
            Route::get('/ofertas', 'ofertas')->name('admin.ofertas');
            Route::delete('/ofertas/{id}', 'destroyOferta')->name('admin.ofertas.destroy');
        });
    });
});
