<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Historial;
use App\Models\Inventario;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Inventarios extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';
    private $paginacion = 10;
    public $nombreComponente;

    public $buscar, $seleccionar_id, $paginaTitulo, $almacen_id, $producto_id, $usuario_id, $stock, $stock_minimo,
    $sucursalNombre, $almacenOrigen, $almacenDestino, $inventarioOrigen, $inventarioDestino, $producto,
    $nombreProducto, $productosAgregar, $productosDisponibles = [];

    public $stockIn = 0;

    protected $rules =
    [
        'almacenOrigen' => 'required',
        'almacenDestino' => 'required',
        'producto_id' => 'required',
        'stock' => 'required',
        'stock_minimo' => 'required',
    ];

    protected $messages = [

        'almacenOrigen.' => 'El Almacen de origen es requerido',
        'almacenDestino.' => 'El Almacen de destino es requerido',
        'producto_id' => 'El producto es requerido',
        'stock.required' => 'El stock es requerido.',
        'stock_minimo.required' => 'El stock minimo es requerido.',
    ];

    public function mount($id)
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Inventarios';
        $this->almacen_id = $id;

        $almacen = Almacen::find($id);
        $this->sucursalNombre = $almacen->descripcion;

        $this->productosAgregar = Producto::leftJoin('inventarios', function ($join) {
            $join->on('productos.id', '=', 'inventarios.producto_id')
                ->where('inventarios.almacen_id', '=', $this->almacen_id);
        })
            ->whereNull('inventarios.id')
            ->select('productos.*')
            ->get();           

    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $almacen = Almacen::find($this->almacen_id);

        if (strlen($this->buscar) > 0) {
            $inventarios =
            Inventario::join('productos as p', 'p.id', 'inventarios.producto_id')
                ->join('almacenes as a', 'a.id', 'inventarios.almacen_id')
                ->with('almacenes', 'productos.categorias')
                ->select('inventarios.*', 'p.descripcion')
                ->where('inventarios.almacen_id', '=', $this->almacen_id)
                ->where('p.descripcion', 'like', '%'.$this->buscar.'%')
                ->orWhere('p.codigo_barras', 'like', '%'.$this->buscar.'%')
                ->orderBy('p.descripcion', 'asc')
                ->paginate($this->paginacion);
        } else {
            $inventarios =
                Inventario::join('productos as p', 'p.id', 'inventarios.producto_id')
                    ->join('almacenes as a', 'a.id', 'inventarios.almacen_id')
                    ->with('almacenes', 'productos.categorias')
                    ->where('inventarios.almacen_id', '=', $this->almacen_id)
                    ->select('inventarios.*', 'p.descripcion')
                    ->orderBy('p.descripcion', 'asc')
                    ->paginate($this->paginacion);
        }

        return view('livewire.inventario.inventario-sucursal', [
            'inventarios' => $inventarios,
            'productos' => Producto::all(),
            'almacenes' => Almacen::all(),
            'almacen' => $almacen,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function agregarProducto($producto_id)
    {
        // crear un nuevo registro en el inventario
        Inventario::create([
            'almacen_id' => $this->almacen_id,
            'producto_id' => $producto_id,
            'usuario_id' => 1,
            'stock' => 0,
            'stock_minimo' => 0,
        ]);

        // actualizar la lista de productos
        $this->productosAgregar = Producto::leftJoin('inventarios', function ($join) {
            $join->on('productos.id', '=', 'inventarios.producto_id')
                ->where('inventarios.almacen_id', '=', $this->almacen_id);
        })
            ->whereNull('inventarios.id')
            ->select('productos.*')
            ->get();

        // mostrar un mensaje de éxito
        $this->resetUI();
        $this->alert('success', 'PRODUCTO AGREGADO CON EXITO', [
            'position' => 'center',
        ]);
    }

    public function agregarTodosLosProductos()
    {
        $productos = Producto::all();

        foreach ($productos as $producto) {
            // Verificar si el producto ya existe en el inventario
            $inventarioExistente = Inventario::where('producto_id', $producto->id)
                ->where('almacen_id', $this->almacen_id)
                ->first();

            if (!$inventarioExistente) {
                $inventario = new Inventario();
                $inventario->producto_id = $producto->id;
                $inventario->almacen_id = $this->almacen_id;
                $inventario->usuario_id = 1;
                $inventario->stock = 0;
                $inventario->stock_minimo = 0;
                // otras columnas que puedas necesitar llenar
                $inventario->save();

                Historial::create([
                    'producto_id' => $producto->id,
                    'usuario_id' => 1,
                    'almacen_id' => $this->almacen_id,
                    'motivo' => 'Alta Inicial',
                    'cantidad' => 0,
                    'tipo' => 'Entrada',
                    'stock_antiguo' => 0,
                    'stock_nuevo' => 0,
                ]);
            }
        }

        // Actualizar la lista de productos
        $productosIngresados = Inventario::pluck('producto_id')->toArray();
        $this->productosDisponibles = Producto::whereNotIn('id', $productosIngresados)->get();

        // Mostrar un mensaje de éxito
        $this->emit('refresh');
        $this->alert('success', 'TODOS LOS PRODUCTOS FUERON AGREGADOS CON ÉXITO', [
            'position' => 'center',
        ]);
        
    }

    public function Edit(Inventario $inventario)
    {
        $this->seleccionar_id = $inventario->id;
        $this->almacen_id = $inventario->almacen_id;
        $this->producto_id = $inventario->producto_id;
        $this->usuario_id = $inventario->usuario_id;
        $this->stock = $inventario->stock;
        $this->stock_minimo = $inventario->stock_minimo;

        $this->emit('modal-show-editar', 'show modal!');

    }

    public function Update()
    {

        $rules =
    [
        'producto_id' => "required|unique:productos,id,{$this->producto_id}",
        'stock' => 'required',
        'stock_minimo' => 'required',
    ];

        $messages = [
            'producto_id' => 'El producto es requerido',
            'stock.required' => 'El stock es requerido.',
            'stock_minimo.required' => 'El stock minimo es requerido.',
        ];

        $this->validate($rules, $messages);
        $inventario = Inventario::find($this->seleccionar_id);
        $inventario->update([
            'almacen_id' => $this->almacen_id,
            'producto_id' => $this->producto_id,
            'usuario_id' => 1,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
        ]);

        $this->resetUI();
        $this->emit('item-editar', 'hide modal!');
        $this->alert('success', 'PRODUCTO EDITADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    public function Destroy(Inventario $inventario)
    {
        
        $this->almacenOrigen = $inventario->almacen_id;
        $this->producto_id = $inventario->producto_id;
        $this->stock = $inventario->stock;       
        
        Historial::create([
        'producto_id' => $this->producto_id,
        'usuario_id' => 1,
        'almacen_id' => $this->almacenOrigen,
        'motivo' => 'Eliminado',
        'cantidad' => $this->stock,
        'tipo' => 'Salida',
        'stock_antiguo' => $this->stock,
        'stock_nuevo' => $this->stock -= $this->stock,
        ]);              
        
        $inventario->delete();         
        $this->resetUI();
        $this->alert('success', 'PRODUCTO ELIMINADO CON EXITO', [
            'position' => 'center',
        ]);
        $this->emit('refresh');
        $this->resetUI();
    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
        'refresh',
    ];

    public function Traslado(Inventario $inventario)
    {
        $this->almacenOrigen = $inventario->almacen_id;
        $this->producto_id = $inventario->producto_id;
        $this->stock = $inventario->stock;
        $this->emit('modal-show-traslado', 'show modal!');
    }

    public function trasladarStock()
    {
        $rules =
    [
        'almacenOrigen' => 'required',
        'almacenDestino' => 'required',
        'producto_id' => 'required',
        'stock' => 'required',
        'stockIn' => 'required',
        'stockIn' => 'numeric',
    ];

        $messages = [

            'almacenDestino.required' => 'El Almacen de destino es requerido',
            'almacenOrigen.required' => 'El Almacen de origen es requerido',
            'producto_id.required' => 'El producto es requerido',
            'stock.required' => 'El stock es requerido.',
            'stockIn.required' => 'La cantidad a trasladar es requerida',
            'stockIn.numeric' => 'El stock es requerido, debe ingresar solo numeros',
        ];

        $this->validate($rules, $messages);

        $producto = Producto::findOrFail($this->producto_id);

        $inventarioOrigen = Inventario::where('producto_id', $this->producto_id)
            ->where('almacen_id', $this->almacenOrigen)
            ->first();

        $inventarioDestino = Inventario::where('producto_id', $this->producto_id)
            ->where('almacen_id', $this->almacenDestino)
            ->first();
        if ($inventarioDestino) {

            // Validar que la sucursal de origen no sea la misma que la de destino
            $almacenOrigen = Almacen::findOrFail($this->almacenOrigen);
            $almacenDestino = Almacen::findOrFail($this->almacenDestino);

            if ($almacenOrigen->id == $almacenDestino->id) {
                $this->resetUI();
                $this->emit('item-traslado', 'Producto Trasladado');
                $this->alert('error', 'EL ALMACEN DE ORIGEN NO PUEDE SER EL MISMO QUE EL DE DESTINO', [
                    'position' => 'center',
                ]);
            }

            // Validar que el stock no sea mayor
            elseif ($inventarioOrigen->stock < $this->stockIn) {
                $this->resetUI();
                $this->emit('item-traslado', 'Producto Trasladado');
                $this->alert('error', 'NO HAY STOCK SUFICIENTE PARA TRASLADAR', [
                    'position' => 'center',
                ]);
            } else {                               

                Historial::create([
                'producto_id' => $this->producto_id,
                'usuario_id' => 1,
                'almacen_id' => $this->almacenOrigen,
                'motivo' => 'Traslado a' .' '. $almacenDestino->descripcion,
                'cantidad' => $this->stockIn,
                'tipo' => 'Salida',
                'stock_antiguo' => $this->stock,
                'stock_nuevo' => $this->stock -= $this->stockIn,
                ]);                

                Historial::create([
                'producto_id' => $this->producto_id,
                'usuario_id' => 1,
                'almacen_id' => $this->almacenDestino,
                'motivo' => 'Traslado de' .' '. $almacenOrigen->descripcion,
                'cantidad' => $this->stockIn,
                'tipo' => 'Entrada',
                'stock_antiguo' => $this->stock,
                'stock_nuevo' => $this->stock += $this->stockIn,
                ]);

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

    public function Sumar(Inventario $inventario)
    {
        $this->almacenOrigen = $inventario->almacen_id;
        $this->producto_id = $inventario->producto_id;
        $this->stock = $inventario->stock;
        $this->emit('modal-show-sumar', 'show modal!');
    }

    public function sumarStock()
    {
        $rules =
    [
        'stockIn' => 'required',
        'stockIn' => 'numeric',
    ];

        $messages = [

            'stockIn.required' => 'El stock es requerido.',
            'stockIn.numeric' => 'El stock es requerido, debe ingresar solo numeros',
        ];

        $this->validate($rules, $messages);

        // Validar que el producto exista en el almacen de origen
        $producto = Producto::findOrFail($this->producto_id);
        $almacenOrigen = Almacen::findOrFail($this->almacenOrigen);
        $inventarioDestino = Inventario::where('producto_id', $this->producto_id)
            ->where('almacen_id', $this->almacenOrigen)
            ->first();

        Historial::create([
        'producto_id' => $this->producto_id,
        'usuario_id' => 1,
        'almacen_id' => $this->almacenOrigen,
        'motivo' => 'Ajuste Stock',
        'cantidad' => $this->stockIn,
        'tipo' => 'Entrada',
        'stock_antiguo' => $this->stock,
        'stock_nuevo' => $this->stock += $this->stockIn,
        ]);

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

    public function Restar(Inventario $inventario)
    {
        $this->almacenOrigen = $inventario->almacen_id;
        $this->producto_id = $inventario->producto_id;
        $this->stock = $inventario->stock;
        $this->emit('modal-show-restar', 'show modal!');
    }

    public function restarStock()
    {
        $rules =
    [
        'stockIn' => 'required',
        'stockIn' => 'numeric',
    ];

        $messages = [

            'stockIn.required' => 'El stock es requerido.',
            'stockIn.numeric' => 'El stock es requerido, debe ingresar solo numeros',
        ];

        $this->validate($rules, $messages);

        // Validar que el producto exista en el almacen de origen
        $producto = Producto::findOrFail($this->producto_id);
        $almacenOrigen = Almacen::findOrFail($this->almacenOrigen);
        $inventarioDestino = Inventario::where('producto_id', $this->producto_id)
            ->where('almacen_id', $this->almacenOrigen)
            ->first();

        if ($inventarioDestino->stock < $this->stockIn) {

            $this->emit('item-restar', 'Producto Trasladado');
            $this->resetUI();
            $this->addError('sinStock', 'No hay suficiente stock.');
            $this->alert('error', 'EL STOCK A DISMIUR SUPERA EL STOCK ACTUAL', [
                'position' => 'center',
            ]);

        } else {

            Historial::create([
            'producto_id' => $this->producto_id,
            'usuario_id' => 1,
            'almacen_id' => $this->almacenOrigen,
            'motivo' => 'Ajuste Stock',
            'cantidad' => $this->stockIn,
            'tipo' => 'Salida',
            'stock_antiguo' => $this->stock,
            'stock_nuevo' => $this->stock -= $this->stockIn,
            ]);

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

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetUI();
    }

    public function resetUI()
    {
        $this->producto_id = '';
        $this->almacenOrigen = '';
        $this->almacenDestino = '';
        $this->stock = 0;
        $this->stockIn = 0;
        $this->stock_minimo = 0;
        $this->seleccionar_id = 0;
        $this->resetValidation();
    }
}
