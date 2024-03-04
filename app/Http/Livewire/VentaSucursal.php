<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use Livewire\Component;
use Livewire\WithPagination;

class VentaSucursal extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $buscar;

    public $paginaTitulo;

    public $nombreComponente;

    private $paginacion = 7;

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Ventas';
    }

    public function render()
    {      
        return view('livewire.venta.venta-sucursal', [
            
            'almacenes' => Almacen::orderBy('descripcion', 'asc')->get(),

        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function verSucursal($id)
    {
        return redirect('ventas/'.$id);
    }
}
