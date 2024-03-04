<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
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

    private $paginacion = 10;

    public $fechaSeleccionada;

    public function mount($id)
    {
        $this->paginaTitulo = 'Historial';
        $this->nombreComponente = 'Productos';
        $this->almacen_id = $id;

        $almacen = Almacen::find($id);
        $this->sucursalNombre = $almacen->descripcion;

        //$this->getCajaDelDia();
        $this->fechaActual = Carbon::now();
    }

    public function render()
    {              
        $almacen = Almacen::find($this->almacen_id);

        if (strlen($this->buscar) > 3) {
            $historialProducto =
            Historial::with('productos')
                    ->where('historiales.almacen_id', '=', $this->almacen_id)
                    ->whereDate('created_at', $this->fechaSeleccionada)
                    ->where('tipo', 'like', '%'.$this->buscar.'%')
                    ->orWhere('motivo', 'like', '%'.$this->buscar.'%')
                    ->select('historiales.*')
                    ->orderBy('created_at', 'desc')
                    ->paginate($this->paginacion);
        } else {
            $historialProducto =
                Historial::with('productos')
                    ->where('historiales.almacen_id', '=', $this->almacen_id)
                    ->whereDate('created_at', $this->fechaSeleccionada)
                    ->select('historiales.*')
                    ->orderBy('created_at', 'desc')
                    ->paginate($this->paginacion);
        }                                

        return view('livewire.historialProductos.historial-productos', [            
            'historiales' => $historialProducto
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }
    
}

