<?php

use App\Http\Controllers\DetalleprestamoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalendarioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('prestamos/export-pdf', [PrestamoController::class, 'exportPdf'])->name('prestamos.exportPdf');
Route::resource('categorias', App\Http\Controllers\CategoriaController::class)->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::resource('personals', PersonalController::class)->middleware('auth');
Route::resource('prestamos', PrestamoController::class)->middleware('auth');
Route::resource('marcas', MarcaController::class)->middleware('auth');
Route::resource('detalleprestamos', DetalleprestamoController::class)->middleware('auth');
Route::put('/prestamos/{id}/mark-as-returned', [PrestamoController::class, 'markAsReturned'])->name('prestamos.markAsReturned');
Route::put('/personals/{id}/edit', [PersonalController::class, 'edit'])->name('personals.edit');
Route::get('/buscar-dni/{dni}', [PersonalController::class, 'buscarDni']);
Route::get('/recursos/{id}/edit', [RecursoController::class, 'edit'])->name(name: 'recursos.edit');
Route::delete('/notificaciones/{id}', [HomeController::class, 'deleteNotification']);
Route::resource('recursos', RecursoController::class)->middleware('auth');
Route::get('/api/personals', [PersonalController::class, 'autocomplete'])->middleware('auth');
Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');





Route::get('/calendarioActivo', [PrestamoController::class, 'obtenerPrestamosActivosCalendario']);



Route::middleware([AdminMiddleware::class])->group(function () {
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/index', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});




/*                                    echarts  */
// web.php

use Illuminate\Support\Facades\DB;

Route::get('/prestamos-obtenerDocentesConMasPrestamos', [ChartController::class, 'getDocentesConPrestamos'])->middleware('auth');
Route::get('/prestamos-obtenerDocentesConPrestamosActivos', [ChartController::class, 'getDocentesConPrestamosActivos'])->middleware('auth');
Route::get('/prestamos-getCategoriasMasUtilizadas', [ChartController::class, 'getCategoriasMasUtilizadas'])->middleware('auth');