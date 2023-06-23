<?php

use App\Http\Livewire\Cajas;
use App\Http\Livewire\Compras;
use App\Http\Livewire\Clientes;
use App\Http\Livewire\Empresas;
use App\Http\Livewire\Almacenes;
use App\Http\Livewire\Depositos;
use App\Http\Livewire\Empleados;
use App\Http\Livewire\Productos;
use App\Http\livewire\Categorias;
use App\Http\Livewire\EditCompras;
use App\Http\Livewire\Inventarios;
use App\Http\Livewire\Proveedores;
use App\Http\Livewire\CajaSucursal;
use App\Http\Livewire\DocAlmacenes;
use App\Http\Livewire\DetalleCompras;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\InventarioSucursal;

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
Route::get('proveedores', Proveedores::class);
Route::get('clientes', Clientes::class);
Route::get('bodega', Depositos::class);
Route::get('docAlmacenes', DocAlmacenes::class);
Route::get('empleados', Empleados::class);
Route::get('inventarios', InventarioSucursal::class);
Route::get('inventario/{id}', Inventarios::class);
Route::get('/productos/export', [Productos::class, 'export']);
Route::get('compras', Compras::class);
Route::get('comprasDetalle', DetalleCompras::class);
Route::get('comprasEdit/{id}', EditCompras::class);
Route::get('cajas', Cajas::class);
Route::get('caja/{id}', CajaSucursal::class);
