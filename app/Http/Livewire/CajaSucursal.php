<?php

namespace App\Http\Livewire;

use App\Models\Caja;
use App\Models\Almacen;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MovimientoCaja;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CajaSucursal extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $buscar, $almacen_id, $caja, $almacen, $movimientos, $monto_ingreso, $motivo_ingreso, $cajaID;

    public $paginaTitulo;

    public $nombreComponente;

    private $paginacion = 7;

    public function mount($id)
    {
        $this->paginaTitulo = 'Detalle';
        $this->nombreComponente = 'Cajas';
        
        $this->almacen_id = $id;

        $almacen = Almacen::find($this->almacen_id);        
        $this->sucursalNombre = $almacen->descripcion;
    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {   
        $this->caja = Caja::with('almacenes', 'usuarios')
                            ->where('almacen_id',$this->almacen_id)
                            ->get();

        $this->movimientos = MovimientoCaja::all();

        return view('livewire.caja.cajaSucursal',[
            'cajas' => $this->caja,
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function ingreso($id)
    {           
        $this->emit('modal-show-ingreso', 'show modal!');
    }

    public function ingresoCaja()
    {      
        $rules =
        [
            'motivo_ingreso' => 'required',
            'monto_ingreso' => 'required',
        ];

        $messages = [

            'monto_ingreso.required' => 'El stock es requerido.',
            'motivo_ingreso.required' => 'El stock es requerido, debe ingresar solo numeros',
        ];

        $this->validate($rules, $messages);        


        $cajaIngreso = Caja::where('almacen_id',$this->almacen_id)->first();        
        $cajaIngreso->monto_ingreso += $this->monto_ingreso;
        $cajaIngreso->save();

        $movimientos = new MovimientoCaja();
        $movimientos->usuario_id = 1;
        $movimientos->almacen_id = $this->almacen_id;
        $movimientos->tipo = "ingreso";
        $movimientos->descripcion = $this->motivo_ingreso;
        $movimientos->monto = $this->monto_ingreso;
        $movimientos->save();              

        $this->emit('modal-hide-ingreso', 'hide modal!');
        $this->resetUI();
         

    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetUI()
    {        
        $this->monto_ingreso = 0;
        $this->motivo_ingreso = '';
        $this->resetPage(); 
    }

}
