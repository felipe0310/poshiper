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

class Compras extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';
    private $paginacion = 7;

    public $productos = [];   
    public $cantidades = [];
    public $precios = [];
    public $busqueda;
    public $carrito = [];
    public $proveedor_id;
    public $documento;
    public $num_documento;
    public $tipoPago;
    public $iva = 1.19;
    public $productoId;
    
    public function mount() 
    {
        $this->productos = Producto::all()->toArray();
        $this->cantidades = [];
        $this->precios = [];              
    }
    
    public function render()
    {         
        return view('livewire.compra.compras',[            
            'proveedores' => Proveedor::all(),
            'precios' => $this->precios,
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
        
    public function agregarAlCarrito($idProducto)
    {
        $producto = Producto::find($idProducto);

        foreach ($this->carrito as $productoSeleccionado) {
            if ($productoSeleccionado['producto_id'] == $idProducto) {
                $this->busqueda = "";            
                $this->alert('error', 'EL PRODUCTO YA EXISTE EN EL CARRITO', ['position' => 'top']);
                return;
            } 
        }        
            
            if ($producto) {
                $this->carrito[] = [
                    'producto_id' => $producto->id,
                    'nombre' => $producto->descripcion,
                    $this->cantidades[] = 0,
                    $this->precios[] = $producto->precio_compra
                ];                       
            }   
            
        $this->alert('success', 'PRODUCTO AGREGADO', ['position' => 'top']);                       
           
        $this->busqueda = "";
        
    }

    public function eliminarDelCarrito($indice)
    {       

        if (isset($this->carrito[$indice])) {
            unset($this->carrito[$indice]);
            $this->carrito = array_values($this->carrito);
        }
        $this->alert('success', 'PRODUCTO ELIMINADO', ['position' => 'top']);                 
        
    }

    public $listeners = [
        'removeItem' => 'eliminarDelCarrito'
    ];
    
    public function calcularTotal()
    {
        $total = 0;

        foreach ($this->carrito as $indice => $item) {
            $subtotal = $this->precios[$indice] * $this->cantidades[$indice];
            $total += $subtotal;
        }

        return $total;
    }

    public function realizarCompra()
    {    
        if(empty($this->carrito)){
        $this->alert('error', 'AGREGE PRODUCTOS A LA COMPRA', ['position' => 'top']);
        return;             
        }            
        
        $rules =
        [
            'proveedor_id' => 'required',            
            'documento' => 'required',
            'num_documento' => 'required',
            'tipoPago' => 'required',
        ];

        $messages = [
            'proveedor_id.required' => 'El proveedor es requerido.',
            'documento.required' => 'El tipo de documento es requerido.',
            'num_documento.required' => 'El numero de documento es requerido.',
            'tipoPago.required' => 'La forma de pago es requerido.',
        ];

        $this->validate($rules, $messages);


        DB::transaction(function () {

            foreach ($this->carrito as $indice => $item) {                                   

                $this->cantidad = $this->cantidades[$indice];
                $this->precio = $this->precios[$indice];
                $this->totalCompra = $this->calcularTotal();
                $this->subtotal = $this->totalCompra / 1.19;
            }

            // Primero, crea una nueva compra.
            $compra = Compra::create([
                'proveedor_id' => $this->proveedor_id,
                'documento' => $this->documento,
                'num_documento' => $this->num_documento,
                'iva' => $this->totalCompra - $this->subtotal,
                'total_compra' => $this->totalCompra,
                'subtotal' => $this->subtotal,
                'tipo_pago' => $this->tipoPago,
            ]);
        

            // Luego, para cada producto en el carrito, crea un detalle de compra
            // y actualiza el stock en el depósito.
            foreach ($this->carrito as $indice => $item) {

                 if (!isset($this->cantidades[$indice]) || !isset($this->precios[$indice])) {
                    continue; // Salta al próximo ciclo si no existen
                }                   

                $this->cantidad = $this->cantidades[$indice];
                $this->precio = $this->precios[$indice];
                $this->subtotal = $this->cantidades[$indice] * $this->precios[$indice];

                $producto = Producto::find($item['producto_id']);

                // Actualizar el stock en el depósito
                $deposito = Deposito::where('producto_id', $producto->id)->first();
                if (!empty($deposito)) {                    
                    $deposito->stock += $this->cantidades[$indice];
                    $deposito->save();                

                // Crea un nuevo detalle de compra.
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $this->cantidades[$indice],
                    'total_compra' => $this->cantidades[$indice] * $this->precios[$indice],
                ]);

                // Actualiza el precio de compra del producto si ha cambiado.
                
                $producto->precio_compra = $this->precios[$indice];
                $producto->save();

            }else{

            $this->alert('error', 'UNO O MAS PRODUCTOS NO EXISTEN EN LA BODEGA', ['position' => 'top']);
            return;

                }                    
            }

            $this->alert('success', 'COMPRA EXISTOSA', ['position' => 'top']);

            // Limpia el carrito después de la compra.         
            $this->carrito = [];
            $this->resetUI();

        });        
    }
    
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
