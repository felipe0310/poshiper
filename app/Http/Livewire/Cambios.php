<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use Livewire\Component;
use Livewire\WithPagination;

class Cambios extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paginaTitulo;

    public $nombreComponente;

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Cambios';
    }

    public function render()
    {
        return view('livewire.cambios.cambios', [

            'almacenes' => Almacen::orderBy('descripcion', 'asc')->get(),

        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function verCambios($id)
    {
        return redirect('cambio/'.$id);
    }
}
