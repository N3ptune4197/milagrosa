<?php

use App\Http\Controllers\DetalleprestamoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\RecursoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::resource('categorias', App\Http\Controllers\CategoriaController::class)->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('personals', PersonalController::class);
Route::resource('prestamos', PrestamoController::class);
Route::resource('marcas', MarcaController::class);
Route::resource('detalleprestamos', DetalleprestamoController::class);
Route::put('/prestamos/{id}/mark-as-returned', [PrestamoController::class, 'markAsReturned'])->name('prestamos.markAsReturned');

Route::get('/buscar-dni/{dni}', [PersonalController::class, 'buscarDni']);

Route::resource('recursos', RecursoController::class);

Route::get('/api/personals', [PersonalController::class, 'autocomplete']);