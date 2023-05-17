<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class Compras extends Component
{
    use LivewireAlert;

    public $proveedor_id, $usuario_id, $docalmacen_id, $num_documento, $serie, $subtotal, $total, $iva, $total_compra, $tipo_pago, $efectivo, $vuelto, $itemsCantidad, $cantidad, $barcode;

    public function mount()
    {
        $this->efectivo = 0;
        $this->vuelto = 0;
        $this->total = Cart::getTotal();
        $this->itemsCantidad = Cart::getTotalQuantity();
    }

    public function render()
    {
        return view('livewire.compra.compras',[
            'cart' => Cart::getContent()->sortBy('nombre')
        ]) 
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function ACash($value)
    {
        $this->efectivo += ($value == 0 ? $this->total : $value);
        $this->efectivo = ($this->efectivo - $this->total);
    }

    protected $listeners = [
        'scan-code' => 'ScanCode',
        'removeItem',
        'clearCart',
        'saveSale'
    ];

    public function ScanCode($barcode, $cantidad = 1)
    {           
        $producto = Producto::where('codigo_barras', $barcode)->first();

        if($producto == null || empty($empty))
        {
            $this->alert('error', 'PRODUCTO NO ENCONTRADO');

        }else{

            if($this->InCart($producto->id))
            {
                $this->increaseQty($producto->id);
                return;
            }

            if($producto->stock < 1)
            {
                $this->alert('error', 'STOCK INSUFICIENTE');
                return;
            }

            Cart::add($producto->id, $producto->descripcion, $producto->precio_venta, $cantidad);
            $this->total = Cart::getTotal();
            $this->alert('success', 'PRODUCTO AGREGADO');

        }
    }

    public function InCart($productoId)
    {
        $existe = Cart::get($productoId);

        if($existe)
            return true;
        else
            return false;
    }

    public function increaseQty($productoId, $cantidad = 1)
    {        
        $producto = Producto::find($productoId);
        $existe = Cart::get($productoId);

        if($existe)
            $this->alert('success', 'CANTIDAD ACTUALIZADA');
        else
            $this->alert('success', 'PRODUCTO AGREGADO');

        if($existe){
            if($producto->stock < ($cantidad + $existe->quantity))
            {
                $this->alert('error', 'STOCK INSUFICIENTE');
                return;
            }
        }

        Cart::add($producto->id, $producto->descripcion, $producto->precio_venta, $cantidad);
            $this->total = Cart::getTotal();
            $this->itemsCantidad = Cart::getTotalQuantity();
            $this->alert('success', 'PRODUCTO AGREGADO');   
    
    }

    public function updateQty($productoId, $cantidad = 1)
    {
        $producto = Producto::find($productoId);
        $existe = Cart::get($productoId);

        if($existe)
            $this->alert('success', 'CANTIDAD ACTUALIZADA');
        else
            $this->alert('success', 'PRODUCTO AGREGADO');

        if($existe)
        {
            if($producto->stock < $cantidad)
            {
                $this->alert('error', 'STOCK INSUFICIENTE');
                return;
            }
        }
        
        $this->removeItem($productoId);

        if($cantidad > 0)
        {
            Cart::add($producto->id, $producto->descripcion, $producto->precio_venta, $cantidad);
            $this->total = Cart::getTotal();
            $this->itemsCantidad = Cart::getTotalQuantity();
            $this->alert('success', 'PRODUCTO AGREGADO');   
        }
        
    }

    public function removeItem($productoId)
    {
        Cart::remove($productoId);
        $this->total = Cart::getTotal();
        $this->itemsCantidad = Cart::getTotalQuantity();
        $this->alert('success', 'PRODUCTO ELIMINADO'); 
    }

    public function decreaseQty($productoId)
    {
        $item = Cart::get($productoId);
        Cart::remove($productoId);

        $newQty = ($item->quantity) -1;

        if($newQty > 0)        
            Cart::add($item->id, $item->descripcion, $item->precio_venta, $newQty);

        $this->total = Cart::getTotal();
        $this->itemsCantidad = Cart::getTotalQuantity();
        $this->alert('success', 'CANTIDAD ACTUALIZADA');

    }

    public function clearCart()
    {
        Cart::clear();
        $this->efectivo = 0;
        $this->vuelto = 0;
        $this->total = 0;
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->alert('success', 'SIN PRODUCTOS PARA LA VENTA');

    }

    public function saveSale()
    {
        if($this->total <= 0){
            $this->alert('error', 'AGREGA PRODUCTOS A LA VENTA');
            return;
        }
        if($this->efectivo <= 0){
            $this->alert('error', 'INGRESE EFECTIVO');
            return;
        }
        if($this->total > $this->efectivo){
            $this->alert('error', 'EL EFECTIVO DEBE SER MAYOR O IGUAL AL TOTAL');
            return;
        }

        DB::beginTransaction();

        try {

            $sale = Sale::create([
                'total' => $this->total,
                'items' => $this->itemsQuantity,
                'efectivo' => $this->efectivo,
                'vuelto' => $this->vuelto,
                'usuario_id' => Auth()->user()->id,

            ]);

            if($sale)
            {
                $items = Cart::getContent();
                foreach ($items as $item)
                {
                    SaleDetail::create([
                        'price' => $this->price,
                        'quantity' => $this->quantity,
                        'producto_id' => $this->id,
                        'sale' => $sale->id
                    ]);

                    $producto = Producto::find($item->id);
                    $producto -> stock = $producto->stock - $item->quantity;
                    $producto->save();

                }
            }

            DB::commit();

            Cart::clear();
            $this->efectivo = 0;
            $this->vuelto = 0;
            $this->total = 0;
            $this->itemsQuantity = Cart::getTotalQuantity();
            $this->alert('success', 'VENTA REGISTRADA EXITOSAMENTE');
            $this->emit('print-ticket', $sale->id);

        } catch (Excepcion $e) {

            DB::rollback();
            $this->alert('error', 'ERROR AL INGRESAR LA VENTA');

        }

    }

    public function printTicket($sale)
    {
        return Redirect::to("print://$sale->id");
    }






}
