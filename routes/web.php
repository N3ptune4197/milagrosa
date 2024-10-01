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
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::resource('personals', PersonalController::class)->middleware('auth');
Route::resource('prestamos', PrestamoController::class)->middleware('auth');
Route::resource('marcas', MarcaController::class)->middleware('auth');
Route::resource('detalleprestamos', DetalleprestamoController::class)->middleware('auth');
Route::put('/prestamos/{id}/mark-as-returned', [PrestamoController::class, 'markAsReturned'])->name('prestamos.markAsReturned');
Route::put('/personals/{id}/edit', [PersonalController::class, 'edit'])->name('personals.edit');
Route::get('/buscar-dni/{dni}', [PersonalController::class, 'buscarDni']);
Route::get('/recursos/{id}/edit', [RecursoController::class, 'edit'])->name(name: 'recursos.edit');

Route::resource('recursos', RecursoController::class)->middleware('auth');

Route::get('/api/personals', [PersonalController::class, 'autocomplete'])->middleware('auth');
