<?php

use App\Http\Livewire\Empresas;
use App\Http\Livewire\Almacenes;
use App\Http\Livewire\Productos;
use App\Http\livewire\Categorias;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('categorias', Categorias::class);
Route::get('productos', Productos::class);
Route::get('empresas', Empresas::class);
Route::get('almacenes', Almacenes::class);