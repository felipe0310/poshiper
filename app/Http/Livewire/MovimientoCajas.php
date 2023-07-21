<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Almacen;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MovimientoCaja;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class MovimientoCajas extends Component
{

    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $fechaSeleccionada;

    public $paginaTitulo;

    public $nombreComponente;

    private $paginacion = 10;

    public function mount($id)
    {
        $this->paginaTitulo = 'Movimientos';
        $this->nombreComponente = 'Cajas';

        $this->almacen_id = $id;

        $almacen = Almacen::find($this->almacen_id);        
        $this->sucursalNombre = $almacen->descripcion;

        //$this->getCajaDelDia();
        $this->fechaActual = Carbon::now();
    }

    public function render()
    {        
        $movCajas = MovimientoCaja::with('almacenes', 'usuarios')
                            ->where('almacen_id',$this->almacen_id)
                            ->whereDate('fecha', $this->fechaSeleccionada)
                            ->orderBy('fecha', 'desc')
                            ->paginate($this->paginacion);

        return view('livewire.movCaja.movimiento-cajas',[
            'movCajas' => $movCajas,
            'almacenes' => Almacen::orderBy('descripcion', 'asc')->get(),
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }        

}
