<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use App\Models\Cambios;
use App\Models\MovimientoCaja;
use App\Models\Producto;
use App\Models\VentaCabecera;
use App\Models\VentaDetalle;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class CambiosSucursal extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $busqueda;

    public $usuario_id;

    public $almacen_id;

    public $productoIngresaId;

    public $productoSaleId;

    public $cantidadIngresa;

    public $cantidadSale;

    public $boletas;

    public $seleccionar_id;

    public $paginaTitulo;

    public $nombreComponente;

    private $paginacion = 7;

    public function mount($id)
    {
        $this->paginaTitulo = 'Detalle';
        $this->nombreComponente = 'Cambios';

        $this->almacen_id = $id;

        $almacen = Almacen::find($this->almacen_id);

        $this->sucursalNombre = $almacen->descripcion;

        // $this->getCajaDelDia();
        $this->fechaActual = Carbon::now();
    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->cambios = Cambios::with('almacenes', 'usuarios')
            ->where('almacen_id', $this->almacen_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->movimientos = MovimientoCaja::all();
        $this->ventaDetalles = VentaDetalle::all();


        return view('livewire.cambios.cambios-sucursal', [
            'cambios' => $this->cambios,
            'ventaDetalles' => $this->ventaDetalles,
            'boletas' => $this->buscarBoleta(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    // public function getCajaDelDia()
    // {
    //     $this->fechaActual = Carbon::now();
    //     $this->estado = 1;
    //     $this->cajaDia = Caja::whereDate('fecha_apertura', $this->fechaActual)
    //                            ->where('almacen_id', $this->almacen_id)
    //                            ->where('estado', $this->estado)
    //                            ->first();

    //     return $this->cajaDia;
    // }

    public function buscarBoleta()
    {
        if (strlen($this->busqueda) > 7) {
            $this->boletas = VentaCabecera::where('serie', 'like', '%'.$this->busqueda.'%')
                ->orWhere('nro_comprobante', 'like', '%'.$this->busqueda.'%')
                ->get()
                ->toArray();
        }
        // Mostrar opciones solo si la búsqueda tiene más de 3 caracteres
        if (strlen($this->busqueda) > 7) {
            $this->showOptions = true;
        } else {
            $this->showOptions = false;
        }
    }

    public function seleccionarDocumento($idVentaDetalle)
    {

        $ventaDetalles = VentaDetalle::where('ventacabecera_id', $idVentaDetalle)->first();

        $this->productoIngresa2 = $ventaDetalles->productos->descripcion;
        $this->cantidadIngresa = $ventaDetalles->cantidad;
    }

    public function openAperturaModal($idVentaDetalle)
    {
        $this->emit('modal-show-apertura', 'show modal!');

        $ventaDetalles = VentaDetalle::where('ventacabecera_id', $idVentaDetalle)->first();
        $this->productoIngresa = $ventaDetalles->productos->descripcion;

    }

    public function aperturaCaja()
    {
        $rules =
        [
            'monto_apertura' => 'required',
        ];

        $messages = [

            'monto_apertura.required' => 'El monto es requerido.',
        ];

        $this->validate($rules, $messages);

        $caja = new Caja();
        $caja->usuario_id = 1;
        $caja->almacen_id = $this->almacen_id;
        $caja->fecha_apertura = Carbon::now();
        $caja->monto_apertura = $this->monto_apertura;
        $caja->fecha_cierre = null;
        $caja->monto_ingreso += 0;
        $caja->monto_egreso += 0;
        $caja->monto_cierre += 0;
        $caja->estado = 1;
        $caja->save();

        $movimientos = new MovimientoCaja();
        $movimientos->usuario_id = 1;
        $movimientos->almacen_id = $this->almacen_id;
        $movimientos->tipo = 'Ingreso';
        $movimientos->descripcion = 'Apertura Caja';
        $movimientos->monto = $this->monto_apertura;
        $movimientos->save();

        $this->emit('modal-hide-apertura', 'hide modal!');
        $this->resetUI();
        $this->alert('success', 'APERTURA DE CAJA EXISTOSO', [
            'position' => 'top',
        ]);

    }

    public function ingreso(Caja $caja)
    {
        $this->cajaId = $caja->id;
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

            'monto_ingreso.required' => 'El monto es requerido.',
            'motivo_ingreso.required' => 'El motivo es requerido',
        ];

        $this->validate($rules, $messages);

        $cajaIngreso = Caja::find($this->cajaId);
        $cajaIngreso->monto_ingreso += $this->monto_ingreso;
        $cajaIngreso->save();

        $movimientos = new MovimientoCaja();
        $movimientos->usuario_id = 1;
        $movimientos->almacen_id = $this->almacen_id;
        $movimientos->tipo = 'Ingreso';
        $movimientos->descripcion = $this->motivo_ingreso;
        $movimientos->monto = $this->monto_ingreso;
        $movimientos->save();

        $this->emit('modal-hide-ingreso', 'hide modal!');
        $this->resetUI();
        $this->alert('success', 'INGRESO DE DINERO EXISTOSO', [
            'position' => 'top',
        ]);
    }

    public function egreso($id)
    {
        if ($this->cajaDia == $this->cajaDia) {
            $this->emit('modal-show-egreso', 'show modal!');
        } else {
            $this->alert('error', 'DEBE ABRIR LA CAJA DEL DIA', [
                'position' => 'top',
            ]);
        }

    }

    public function egresoCaja()
    {
        $rules =
        [
            'motivo_egreso' => 'required',
            'monto_egreso' => 'required',
        ];

        $messages = [

            'monto_egreso.required' => 'El monto es requerido.',
            'motivo_egreso.required' => 'El motivo es requerido',
        ];

        $this->validate($rules, $messages);

        $cajaEgreso = Caja::where('estado', 1)->first();
        $cajaEgreso->monto_egreso += $this->monto_egreso;
        $cajaEgreso->save();

        $movimientos = new MovimientoCaja();
        $movimientos->usuario_id = 1;
        $movimientos->almacen_id = $this->almacen_id;
        $movimientos->tipo = 'Retiro';
        $movimientos->descripcion = $this->motivo_egreso;
        $movimientos->monto = $this->monto_egreso;
        $movimientos->save();

        $this->resetUI();
        $this->emit('modal-hide-egreso', 'hide modal!');
        $this->alert('success', 'RETIRO DE DINERO EXISTOSO', [
            'position' => 'top',
        ]);
    }

    public function cerrarModalOpen($id)
    {
        $this->cajaCierreID = Caja::find($id);
        $this->emit('modal-show-cierre', 'hide modal!');
    }

    public function cerrarCaja()
    {
        $rules =
        [
            'monto_cierre' => 'required',
        ];

        $messages = [

            'monto_cierre.required' => 'El monto es requerido.',
        ];

        $this->validate($rules, $messages);

        $cajaCierre = Caja::find($this->cajaCierreID)->first();
        $cajaCierre->estado = 0;
        $cajaCierre->fecha_cierre = Carbon::now();
        $cajaCierre->monto_cierre = $this->monto_cierre;
        $cajaCierre->save();

        $movimientos = new MovimientoCaja();
        $movimientos->usuario_id = 1;
        $movimientos->almacen_id = $this->almacen_id;
        $movimientos->tipo = 'Retiro';
        $movimientos->descripcion = 'Cierre Caja';
        $movimientos->monto = $this->monto_cierre;
        $movimientos->save();

        $this->emit('modal-hide-cierre', 'hide modal!');
        $this->resetUI();
        $this->alert('success', 'CAJA CERRADA EXISTOSAMENTE', [
            'position' => 'top',
        ]);

    }

    public function movimientoCaja($id)
    {
        return redirect('movimientoCaja/'.$id);
    }

    protected $listeners = [
        'cerrarModalOpen',
    ];

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetUI()
    {
        $this->monto_ingreso = 0;
        $this->motivo_ingreso = '';
        $this->monto_egreso = 0;
        $this->motivo_egreso = '';
        $this->monto_cierre = 0;
        $this->monto_apertura = 0;
        $this->resetPage();
    }
}
