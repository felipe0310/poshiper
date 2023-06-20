<?php

namespace App\Http\Livewire;

use App\Models\Compra;
use Livewire\Component;
use App\Models\Deposito;
use App\Models\Producto;
use App\Models\Proveedor;
use Livewire\WithPagination;
use App\Models\DetalleCompra;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EditCompras extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';
    public $paginaTitulo;
    public $nombreComponente;
    private $paginacion = 10;
    public $busqueda;
    public $detalle_compras = [];
    public $depositos;
    public $productos=[];
    public $compraId;
    public $compra;
    public $cantidades = [];
    public $precios = [];
    public $proveedor_id;
    public $documento;
    public $num_documento;
    public $tipoPago;
    public $iva = 1.19;

    public function mount($id)
    {   
        $this->compra = Compra::find($id);

        $this->detalle_compras = DetalleCompra::where('compra_id', $id)
                                ->with('producto')->get()->toArray(); 
        
        $this->productos = Producto::all()->toArray();
        $this->cantidades = [];
        $this->precios = [];      
    }

    public function render()
    {
        
        $this->depositos = Deposito::all();

        if (strlen($this->busqueda) > 3) {            
            $compras = Compra::join('proveedores as pr', 'pr.id', 'compras.proveedor_id')
                ->select('compras.*', 'pr.nombre as proveedores')
                ->where('compras.documento', 'like', '%'.$this->busqueda.'%')
                ->orWhere('compras.num_documento', 'like', '%'.$this->busqueda.'%')
                ->orWhere('pr.nombre', 'like', '%'.$this->busqueda.'%')
                ->orderBy('compras.created_at', 'asc')
                ->paginate($this->paginacion);

        } else {
            $compras = Compra::join('proveedores as pr', 'pr.id', 'compras.proveedor_id')
                ->select('compras.*', 'pr.nombre as proveedores')
                ->orderBy('created_at', 'asc')
                ->paginate($this->paginacion);
        }

        return view('livewire.detalleCompra.editCompra',[            
        'compras' => $compras,
        'productos' => Producto::all(),
        'proveedores' => Proveedor::all(),
        'total' => $this->calcularTotal(),
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function updatedBusqueda()
    {
        $this->buscarProducto();
    }

    public function buscarProducto()
    {
        if (!empty($this->busqueda)) {
            $this->productos = Producto::where('descripcion', 'like', '%' . $this->busqueda . '%')
                                       ->orWhere('codigo_barras', 'like', '%' . $this->busqueda . '%')
                                       ->get()
                                       ->toArray();
        } else {
            $this->productos = Producto::all()->toArray();
        }
    }

    public function seleccionarProducto($productoId)
    {
        $this->productoBuscado = Producto::find($productoId);

        foreach ($this->detalle_compras as $productoSeleccionado) {
            if ($productoSeleccionado['producto_id'] == $productoId) {
                $this->busqueda = "";            
                $this->alert('error', 'EL PRODUCTO YA EXISTE EN EL CARRITO', ['position' => 'top']);
                return;
            } 
        }   

        $this->agregarAlCarrito($this->productoBuscado);
    }

    public function agregarAlCarrito($producto = null)
    {      
        $this->detalle_compras[] = [
            'producto_id' => optional($producto)->id,
            'cantidad' => 0, 
            'producto' => [
                'descripcion' => optional($producto)->descripcion, 
                'precio_compra' => optional($producto)->precio_compra
            ]
        ];                  
            
        $this->alert('success', 'PRODUCTO AGREGADO', ['position' => 'top']);
        $this->calcularTotal();                       
           
        $this->busqueda = "";
    }

    public function eliminarDelCarrito($index)
    { 
        unset($this->detalle_compras[$index]);
        $this->detalle_compras = array_values($this->detalle_compras);
        $this->alert('success', 'PRODUCTO ELIMINADO', ['position' => 'top']);
        $this->calcularTotal();                 
        
    }

    protected $rules = [
        'compra.proveedor_id' => 'required',
        'compra.documento' => 'required',
        'compra.num_documento' => 'required',
        'compra.tipo_pago' => 'required'
        // Resto de las reglas de validación para los campos de la compra
    ];
    
    public function update()
    {
        // Valida los datos de la compra y sus detalles
        $this->validate();
        

    // Comienza una transacción de base de datos

    // Actualizar el stock en el depósito               
    foreach ($this->detalle_compras as $detalle) {
        $producto = Producto::find($detalle['producto_id']);

        if ($producto) {
            // Obtener el depósito asociado al producto
            $deposito = Deposito::where('producto_id', $producto->id)->first();

            if ($deposito) {
                // Sumar la cantidad de productos comprados al stock actual en el depósito
                $cantidad = $detalle['cantidad'];
                $deposito->stock = $cantidad;
                $deposito->save();
            }
        }
    }   

    DB::beginTransaction();

    try {
        // Actualiza la compra

        $totalCompra = $this->calcularTotal();
        $subTotal = $totalCompra/1.19;
        $iva = $totalCompra - $subTotal;

        $this->compra->save();
        $this->compra->update(['total_compra' => $totalCompra,
                                'subtotal' => $subTotal,
                                'iva' => $iva ]);

        // Actualiza los detalles de la compra
        foreach ($this->detalle_compras as $detalle) {
            $detalleCompra = DetalleCompra::where('compra_id', $this->compra->id)
                ->where('producto_id', $detalle['producto_id'])
                ->first();

            if ($detalleCompra) {
                // Si el detalle ya existe, actualiza la cantidad
                $detalleCompra->cantidad = $detalle['cantidad'];
                $detalleCompra->producto_id = $detalle['producto_id'];
                $detalleCompra->save();
            } else {
                // Si el detalle no existe, crea uno nuevo
                DetalleCompra::create([
                    'compra_id' => $this->compra->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                ]);
            }
        }

        // Commit the transaction
        DB::commit();

        $this->alert('success', 'COMPRA ACTUALIZADA', ['position' => 'top']);        
        return redirect('comprasDetalle');

    } catch (\Exception $e) {
        // Rollback the transaction
        DB::rollback();

        $this->alert('error', 'ERROR AL ACTUALIZAR', ['position' => 'top']); 
    }
        
    }   

    public function Destroy($compraId)
    {
        $compra = Compra::find($compraId);
        $detalle_compras = DetalleCompra::where('compra_id', $compraId)->get();

        foreach ($detalle_compras as $detalle) {
            $deposito = Deposito::where('producto_id', $detalle->producto_id)->first();
            
            if ($deposito) {
                $deposito->stock -= $detalle->cantidad;
                $deposito->save();
            }

            // Aquí eliminamos el registro de detalle de compra
            $detalle->delete();
        }

        // Ahora que todos los detalles de compra asociados han sido eliminados,
        // podemos eliminar la compra
        $compra->delete();
        $detalle_compras->delete();

        $this->emit('item-delete', 'Categoria Eliminada');
        $this->alert('success', 'COMPRA ELIMINADA CON EXITO', [
            'position' => 'center',
        ]);

    }

    public function calcularTotal()
    {
        $total = 0;

        foreach ($this->detalle_compras as $detalle) {
            $cantidad = $detalle['cantidad'];
            $precio = floatval($detalle['producto']['precio_compra']);

            $subtotal = $cantidad * $precio;
            $total += $subtotal;
        }

        return $total;
    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
        'removeItem' => 'eliminarDelCarrito'    
    ];

    public function resetUI()
    {
        $this->proveedor_id = "";
        $this->documento = "";
        $this->num_documento = "";
        $this->tipoPago = "";
        $this->cantidad = "";
        $this->cantidades = [];
        $this->carrito = [];
        $this->totalCompra="";
        $this->resetValidation();

    }



}
