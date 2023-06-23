<?php

namespace App\Http\Livewire;

use App\Models\Caja;
use App\Models\Almacen;
use Livewire\Component;
use Livewire\WithPagination;

class Cajas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paginaTitulo;

    public $nombreComponente;

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Cajas';
    }

    public function render()
    {
        return view('livewire.caja.cajas', [
            
            'almacenes' => Almacen::orderBy('descripcion', 'asc')->get(),

        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function verCaja($id)
    {
        return redirect('caja/'.$id);
    }
}
