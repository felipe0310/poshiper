<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use App\Models\Deposito;
use App\Models\Inventario;
use App\Models\Producto;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Depositos extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $buscar;

    public $seleccionar_id;

    public $paginaTitulo;

    public $nombreComponente;

    public $productosDisponibles = [];

    public $almacen_id;

    public $producto_id;

    public $usuario_id;

    public $stock;

    public $stock_minimo;

    public $sucursalNombre;

    public $almacenOrigen;

    public $almacenDestino;

    public $inventarioOrigen;

    public $inventarioDestino;

    public $producto;

    public $nombreProducto;

    public $stockIn = 0;

    public $productosAgregar;

    private $paginacion = 15;

    protected $rules =
    [
        'producto_id' => 'required|unique:depositos',
        'stock' => 'required',

    ];

    protected $messages = [
        'producto_id.required' => 'El producto es requerido.',
        'stock.required' => 'El Stock no puede ser vacio, sin Stock ingrese 0.',
        'producto_id.unique' => 'El producto ya existe',
    ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Bodega';

        $productosIngresados = Deposito::pluck('producto_id')->toArray();
        $this->productosDisponibles = Producto::whereNotIn('id', $productosIngresados)->get();

    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (strlen($this->buscar) > 0) {
            $depositos = Deposito::join('productos as p', 'p.id', 'depositos.producto_id')
                ->select('depositos.*', 'p.descripcion as productos_descripcion', 'p.codigo_barras as productos_codigo')
                ->where('p.descripcion', 'like', '%'.$this->buscar.'%')
                ->orWhere('p.codigo_barras', 'like', '%'.$this->buscar.'%')
                ->orderBy('p.descripcion', 'asc')
                ->paginate($this->paginacion);
        } else {
            $depositos = Deposito::join('productos as p', 'p.id', 'depositos.producto_id')
                ->select('depositos.*', 'p.descripcion as productos_descripcion', 'p.codigo_barras as productos_codigo')
                ->orderBy('p.descripcion', 'asc')
                ->paginate($this->paginacion);
        }

        return view('livewire.deposito.depositos', [
            'depositos' => $depositos,
            'productos' => Producto::orderBy('descripcion', 'asc')->get(),
            'almacenes' => Almacen::all(),
            'inventarios' => Inventario::all(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');

    }

    public function Edit(Deposito $deposito)
    {
        $this->seleccionar_id = $deposito->id;
        $this->producto_id = $deposito->producto_id;
        $this->stock = $deposito->stock;

        $this->emit('modal-show-editar', 'show modal!');

    }

    public function Store($producto_id)
    {
        $deposito = Deposito::create([
            'producto_id' => $producto_id,
            'stock' => 0,
        ]);

        $productosIngresados = Deposito::pluck('producto_id')->toArray();
        $this->productosDisponibles = Producto::whereNotIn('id', $productosIngresados)->get();

    }

    public function agregarTodosLosProductos()
    {
        $productos = Producto::all();

        foreach ($productos as $producto) {
            // Suponiendo que tienes una relación entre Productos e Inventario
            $deposito = new Deposito();
            $deposito->producto_id = $producto->id;
            $deposito->stock = 0;
            // otras columnas que puedas necesitar llenar
            $deposito->save();
        }

        // actualizar la lista de productos
        $productosIngresados = Deposito::pluck('producto_id')->toArray();
        $this->productosDisponibles = Producto::whereNotIn('id', $productosIngresados)->get();

        // mostrar un mensaje de éxito
        $this->resetUI();
        $this->alert('success', 'TODOS LOS PRODUCTOS FUERON AGREGADO CON EXITO', [
            'position' => 'center',
        ]);
    }












    public function Update()
    {
        $rules =
    [
        'producto_id' => "required|unique:depositos,producto_id,{$this->seleccionar_id}",
        'stock' => 'required',
        'stock' => 'numeric',

    ];
        $messages = [
            'producto_id.required' => 'El producto es requerido.',
            'stock.required' => 'El Stock no puede ser vacio, sin Stock ingrese 0.',
            'stock.numeric' => 'El Stock no puede ser negativo.',
            'producto_id.unique' => 'El producto ya existe',
        ];

        $this->validate($rules, $messages);
        $deposito = Deposito::find($this->seleccionar_id);
        $deposito->update([
            'producto_id' => $this->producto_id,
            'stock' => $this->stock,
        ]);

        $this->resetUI();
        $this->emit('item-editar', 'Deposito Actualizado');

    }

    public function Destroy(Deposito $deposito)
    {
        $deposito->delete();
        $this->resetUI();
        $this->alert('success', 'PRODUCTO ELIMINADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    public function Restar(Deposito $deposito)
    {
        $this->producto_id = $deposito->producto_id;
        $this->stock = $deposito->stock;
        $this->emit('modal-show-restar', 'show modal!');
    }

    public function restarStock()
    {
        $rules =
    [
        'stockIn' => 'required',
    ];

        $messages = [

            'stockIn.required' => 'El stock es requerido.',
        ];

        $this->validate($rules, $messages);
        // Validar que el producto exista en el almacen de origen
        $producto = Producto::findOrFail($this->producto_id);
        $inventarioDestino = Deposito::where('producto_id', $this->producto_id)
            ->first();

        if ($this->stockIn == 0) {
            $this->emit('item-restar', 'Producto Trasladado');
            $this->resetUI();
        } elseif ($inventarioDestino->stock < $this->stockIn) {

            $this->emit('item-restar', 'Producto Trasladado');
            $this->resetUI();
            $this->addError('sinStock', 'No hay suficiente stock.');
            $this->alert('error', 'EL STOCK A DISMIUR SUPERA EL STOCK ACTUAL', [
                'position' => 'center',
            ]);

        } else {

            $inventarioDestino->stock -= $this->stockIn;
            $inventarioDestino->save();
            // Redireccionar a la página de inventario
            $this->resetUI();
            $this->emit('item-restar', 'Producto Trasladado');
            $this->alert('success', 'STOCK DISMINUIDO CON EXITO', [
                'position' => 'center',
            ]);

        }

    }

    public function Sumar(Deposito $deposito)
    {
        $this->producto_id = $deposito->producto_id;
        $this->stock = $deposito->stock;
        $this->emit('modal-show-sumar', 'show modal!');
    }

    public function sumarStock()
    {
        $rules =
    [
        'stockIn' => 'required',
    ];

        $messages = [

            'stockIn.required' => 'El stock es requerido.',

        ];

        $this->validate($rules, $messages);

        if ($this->stockIn == 0) {
            $this->resetUI();
            $this->emit('item-sumar', 'Producto Trasladado');
        } else {
            // Validar que el producto exista en el almacen de origen
            $producto = Producto::findOrFail($this->producto_id);
            $inventarioDestino = Deposito::where('producto_id', $this->producto_id)
                ->first();

            // Agregar stock al almacen de destino
            $inventarioDestino->stock += $this->stockIn;
            $inventarioDestino->save();

            // Redireccionar a la página de inventario
            $this->resetUI();
            $this->emit('item-sumar', 'Producto Trasladado');
            $this->alert('success', 'STOCK AUMENTADO CON EXITO', [
                'position' => 'center',
            ]);
        }
    }

    public function Traslado(Deposito $deposito)
    {
        $this->producto_id = $deposito->producto_id;
        $this->stock = $deposito->stock;
        $this->emit('modal-show-traslado', 'show modal!');
    }

    public function trasladarStock()
    {
        $rules =
    [
        'almacenDestino' => 'required',
        'producto_id' => 'required',
        'stock' => 'required',
        'stockIn' => 'required',
    ];

        $messages = [

            'almacenDestino.required' => 'El Almacen de destino es requerido',
            'producto_id.required' => 'El producto es requerido',
            'stock.required' => 'El stock es requerido.',
            'stockIn.required' => 'La cantidad a trasladar es requerida',
        ];

        $this->validate($rules, $messages);

        $inventarioOrigen = Deposito::where('producto_id', $this->producto_id)
            ->where('stock', $this->stock)
            ->first();

        $inventarioDestino = Inventario::where('producto_id', $this->producto_id)
            ->where('almacen_id', $this->almacenDestino)
            ->first();

        if ($this->stockIn == 0) {
            $this->resetUI();
            $this->emit('item-traslado', 'Producto Trasladado');
        } elseif ($inventarioDestino) {

            // Validar que el stock no sea mayor
            if ($inventarioOrigen->stock < $this->stockIn) {
                $this->resetUI();
                $this->emit('item-traslado', 'Producto Trasladado');
                $this->alert('error', 'NO HAY STOCK SUFICIENTE PARA TRASLADAR', [
                    'position' => 'center',
                ]);
            } else {

                // Restar stock del almacen de origen
                $inventarioOrigen->stock -= $this->stockIn;
                $inventarioOrigen->save();

                // Agregar stock al almacen de destino
                $inventarioDestino->stock += $this->stockIn;
                $inventarioDestino->save();

                // Redireccionar a la página de inventario
                $this->resetUI();
                $this->emit('item-traslado', 'Producto Trasladado');
                $this->alert('success', 'PRODUCTO TRASLADADO CON EXITO', [
                    'position' => 'center',
                ]);
            }

        } else {
            $this->resetUI();
            $this->emit('item-traslado', 'Producto Trasladado');
            $this->alert('error', 'EL PRODUCTO NO EXISTE EN EL ALMACEN DE DESTINO', [
                'position' => 'center',
            ]);
        }

    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetUI();

    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
        'refresh',
    ];

    public function resetUI()
    {
        $this->resetValidation();
        $this->producto_id = '';
        $this->almacenDestino = '';
        $this->stock = 0;
        $this->stockIn = 0;
        $this->seleccionar_id = 0;

    }
}
