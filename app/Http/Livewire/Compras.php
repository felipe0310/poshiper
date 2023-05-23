<?php

namespace App\Http\Livewire;

use App\Models\Compra;
use Livewire\Component;
use App\Models\Deposito;
use App\Models\Producto;
use App\Models\Proveedor;
use Livewire\WithPagination;
use App\Models\DetalleCompra;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Compras extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';
    private $paginacion = 7;

    public $productId;
    public $cantidad;
    public $precioCompra;
    public $proveedorId, $documento, $numDocumento, $iva, $total_compra, $tipoPago, $producto_id;
    public $productosAComprar = [];
    public $search;
    public $totalCompra = 0;  
    
    public function mount(Producto $producto)
    {
        $this->productoPrecio = $producto -> precio_compra;
        $this->productoNombre = $producto -> descripcion;
    }
    
    public function render()
    {   
        if(strlen($this->search) > 2){
            $productos = Producto::where('descripcion', 'like', '%'.$this->search.'%')
                         ->orWhere('codigo_barras', 'like', '%'.$this->search.'%')->get();
        }else{
            $productos = Producto::all();
        }

        return view('livewire.compra.compras',[
            'productos' => $productos,
            'proveedores' => Proveedor::all(),

        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function store()
    {
        $totalCompra = 0;

        foreach ($this->productosAComprar as $productoAComprar){
            $this->totalCompra = $productoAComprar['cantidad'] * $productoAComprar['precio'];
        }

        // Crear la compra
        $compra = new Compra();
        $compra->proveedor_id = $this->proveedorId;
        $compra->documento = $this->documento;
        $compra->num_documento = $this->numDocumento;
        $compra->subtotal = $totalCompra / 1.19;
        $compra->iva = 1.19;
        $compra->total_compra = $totalCompra;
        $compra->tipo_pago = $this->tipoPago;
        $compra->save();

        foreach ($this->productosAComprar as $productoAComprar) {

        $producto = Producto::find($productoAComprar['producto_id']);

        // Aquí aumentamos el stock
        $deposito = Deposito::where('producto_id', $productoAComprar['producto_id'])->first();
        $deposito->stock += $productoAComprar['cantidad'];
        $deposito->save();

        // Aquí actualizamos el precio de compra
        $producto->precio_compra = $productoAComprar['precio'];
        $producto->save();        

        // Crear el detalle de la compra
        $detalle = new DetalleCompra();
        $detalle->compra_id = $compra->id;
        $detalle->nombre_producto = $producto->descripcion;
        $detalle->cantidad = $productoAComprar['cantidad'];
        $detalle->total_compra = $productoAComprar['cantidad'] * $productoAComprar['precio'];
        $detalle->save();        
        
        }
        
        // Limpiar la lista de productos a comprar
        $this->resetUI();

        session()->flash('message', 'Compra realizada con éxito.');
        
    }

    public function agregarProducto($id)
    {           
        $producto = Producto::find($id);

        $this->productosAComprar[] = ['producto_id' => $id, 'cantidad' => 0, 'precio' => $producto->precio_compra];
        $this->search ="";        
    }

    public function eliminarProducto($index)
    {
        unset($this->productosAComprar[$index]);
        $this->productosAComprar = array_values($this->productosAComprar);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetUI()
    {
        $this->proveeedor_id = "";
        $this->search = "";
        $this->documento = "";
        $this->producto_id = "";
        $this->cantidad = "";
        $this->precio = "";
        $this->tipoPago = "";
        $this->numDocumento = "";
        $this->productosAComprar = [];

    }

}
