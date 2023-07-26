<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Historial;
use App\Models\Inventario;
use Livewire\WithPagination;

class HistorialProductos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $buscar;

    public $paginaTitulo;

    public $nombreComponente;

    private $paginacion = 7;

    public function mount($id)
    {
        $this->paginaTitulo = 'Historial';
        $this->nombreComponente = 'Productos';
        $this->almacen_id = $id;

        $almacen = Almacen::find($id);
        $this->sucursalNombre = $almacen->descripcion;
    }

    public function render()
    {              
        $almacen = Almacen::find($this->almacen_id);

        if (strlen($this->buscar) > 3) {
            $historialProducto =
            Historial::with('productos')
                    ->where('historiales.almacen_id', '=', $this->almacen_id)
                    ->where('tipo', 'like', '%'.$this->buscar.'%')
                    ->select('historiales.*')
                    ->orderBy('producto_id', 'asc')
                    ->paginate($this->paginacion);
        } else {
            $historialProducto =
                Historial::with('productos')
                    ->where('historiales.almacen_id', '=', $this->almacen_id)
                    ->select('historiales.*')
                    ->orderBy('producto_id', 'asc')
                    ->paginate($this->paginacion);
        }                                

        return view('livewire.historialProductos.historial-productos', [            
            'historiales' => $historialProducto
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
    
}

