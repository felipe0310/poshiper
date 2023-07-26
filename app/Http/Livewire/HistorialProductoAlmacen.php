<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use Livewire\Component;

class HistorialProductoAlmacen extends Component
{   
    public $paginaTitulo;

    public $nombreComponente;

    public function mount()
    {
        $this->paginaTitulo = 'Historial';
        $this->nombreComponente = 'Productos';
    }

    public function render()
    {       
        return view('livewire.historialProductos.historial-productos-almacen', [
            
            'almacenes' => Almacen::orderBy('descripcion', 'asc')->get(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function verSucursal($id)
    {
        return redirect('historialProductos/'.$id);
    }
}

