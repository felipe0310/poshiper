<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Caja;
use App\Models\Cliente;
use Livewire\Component;
use App\Models\Producto;
use App\Models\DocAlmacen;
use App\Models\Inventario;
use Livewire\WithPagination;
use App\Models\DetalleCompra;
use App\Models\VentaCabecera;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class VentaCabeceras extends Component
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
    public $cliente_id;
    public $documento = 'Boleta';
    public $num_documento;
    public $tipoPago;
    public $iva = 1.19;
    public $productoId;
    public $cajaDia;
    public $almacen_id;
    public $docAlmacenes;
    public $numDocumento;
    public $numeroDocumento;
    public $busquedaCliente;
    public $selectedClientId;
    public $pagoEfectivo = 0;

    public function mount($id) 
    {
        $this->productos = Producto::all()->toArray();
        $this->cantidades = [];
        $this->precios = [];
        $this->almacen_id = $id;
        $this->getCajaDelDia();
        $this->loadNumeroDocumento();          
    }
    
    public function render()
    {   
        $this->docAlmacenes = DocAlmacen::where('almacen_id', $this->almacen_id)
                                         ->get();  
                                         
        $clientes = Cliente::where('nombre', 'LIKE', '%' . $this->busquedaCliente . '%')
                            ->orWhere('apellido', 'LIKE', '%' . $this->busquedaCliente . '%')
                            ->get();                                
        
        return view('livewire.venta.venta-cabeceras',[            
            'clientes' => $clientes,
            'docAlmacenes' => $this->docAlmacenes,
            'precios' => $this->precios, 
            'total' => $this->calcularTotal(),
            'vuelto' => $this->calcularVuelto()

        ])
        ->extends('layouts.theme.app')
        ->section('content');
    } 

    public function selectClient($clientId)
    {
        $this->selectedClientId = $clientId;
    }      

    public function updatedDocumento()    {
        
        $this->loadNumeroDocumento(); // Cargamos el número de documento al cambiar el tipo
    }

    private function loadNumeroDocumento()
    {
        $numDocumento = DocAlmacen::where('almacen_id', $this->almacen_id)
                                    ->where('documento', $this->documento)
                                    ->first();

        if ($numDocumento) {
            $this->numeroDocumento = str_pad($numDocumento->cantidad, 8, '0', STR_PAD_LEFT);
        } else {
            $this->numeroDocumento = str_pad(0, 8, '0', STR_PAD_LEFT); // o algún valor predeterminado si no se encuentra el número
        }
    }

    public function incrementarNumeroDocumento()
    {
        $numDocumento = DocAlmacen::where('almacen_id', $this->almacen_id)
                                    ->where('documento', $this->documento)
                                    ->first();

        if ($numDocumento) {
            $numDocumento->increment('cantidad');
            $this->loadNumeroDocumento(); // Actualizar el número en el componente
        }
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
                    $this->cantidades[] = 1,
                    $this->precios[] = $producto->precio_venta
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
        'removeItem' => 'eliminarDelCarrito',
                        'updatedDocumento',
                        'resetSelect2'
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

    public function calcularVuelto()
    {
        $vuelto = $this->pagoEfectivo - $this->calcularTotal();
        
        return $vuelto;

    }

    public function getCajaDelDia()
    {
        $this->fechaActual = Carbon::now();
        $this->estado = 1;
        $this->cajaDia = Caja::whereDate('fecha_apertura', $this->fechaActual)
                               ->where('almacen_id', $this->almacen_id)
                               ->where('estado', $this->estado)
                               ->first();

        return $this->cajaDia;
    }

    public function realizarVenta()
    {                    
    
        if(empty($this->carrito)){
        $this->alert('error', 'AGREGE PRODUCTOS A LA VENTA', ['position' => 'top']);
        return;             
        }            
        
        $rules =
        [
            'cliente_id' => 'required',            
            'documento' => 'required',
            'tipoPago' => 'required',
        ];

        $messages = [
            'cliente_id.required' => 'El cliente es requerido.',
            'documento.required' => 'El tipo de documento es requerido.',
            'tipoPago.required' => 'La forma de pago es requerido.',
        ];

        $this->validate($rules, $messages);        

        if($this->tipoPago === 'Efectivo'){
            $vuelto = $this->calcularVuelto();
            if ($vuelto < 0) {
                $vuelto = $this->calcularVuelto();
                $this->alert('error', 'Verifique el monto recibido', ['position' => 'top']);
                return;
            }
        }                   
        
        DB::transaction(function () {

            foreach ($this->carrito as $indice => $item) {                                   

                $this->cantidad = $this->cantidades[$indice];
                $this->precio = $this->precios[$indice];
                $this->totalCompra = $this->calcularTotal();
                $this->subtotal = $this->totalCompra / 1.19;
            }            

            // Primero, crea una nueva compra.
            $venta = VentaCabecera::create([
                'cliente_id' => $this->cliente_id,
                'almacen_id' => $this->almacen_id,
                'usuario_id' => 1,
                'docalmacen_id' => $this->documento,
                'caja_id' => $this->cajaDia->id,
                'serie' => ($this->documento === 'Boleta') ? 'B' : 'F',
                'nro_comprobante' => $this->numeroDocumento,
                'descripcion' => 'Venta',
                'pago_efectivo' => ($this->tipoPago === 'Efectivo') ? $this->totalCompra : 0,
                'pago_tarjeta' => ($this->tipoPago === 'Tarjeta') ? $this->totalCompra : 0,
                'pago_transferencia' => ($this->tipoPago === 'Transferencia') ? $this->totalCompra : 0,
                'pago_credito' => ($this->tipoPago === 'Credito') ? $this->totalCompra : 0,
                'delivery' => 0,
                'iva' => $this->totalCompra - $this->subtotal,
                'total_venta' => $this->totalCompra,
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
                $inventario = Inventario::where('producto_id', $producto->id)->first();
                if (!empty($inventario)) {
                    $inventario->almacen_id = $this->almacen_id;                    
                    $inventario->stock -= $this->cantidades[$indice];
                    $inventario->save();                

                // Crea un nuevo detalle de compra.
                //

                // Actualiza el precio de compra del producto si ha cambiado.
                
                $producto->precio_compra = $this->precios[$indice];
                $producto->save();

            }else{

            $this->alert('error', 'UNO O MAS PRODUCTOS NO EXISTEN EN LA BODEGA', ['position' => 'top']);
            return;

                }                    
            }          

            $this->alert('success', 'VENTA EXISTOSA', ['position' => 'top']);

            $this->incrementarNumeroDocumento();
            

            // Limpia el carrito después de la compra.         
            $this->carrito = [];
            $this->resetUI();
        });        
    }        
    
    public function resetUI()
    {   
        $this->cliente_id = null;     
        $this->documento = 'Boleta';
        $this->num_documento = $this->loadNumeroDocumento();
        $this->tipoPago = " ";
        $this->cantidad = " ";
        $this->cantidades = [];
        $this->carrito = [];
        $this->precios = [];
        $this->totalCompra=" ";        
        $this->pagoEfectivo = 0;
        $this->resetValidation();      

    }

}

