<?php

use App\Http\Controllers\PersonalController;
use App\Http\Controllers\TipodocController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::resource('categorias', App\Http\Controllers\CategoriaController::class)->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('tipodocs', TipodocController::class);
Route::resource('personals', PersonalController::class);
