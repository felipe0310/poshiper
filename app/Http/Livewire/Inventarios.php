<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Inventarios extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;
    private $paginacion = 7;

    public $almacen_id, $producto_id, $usuario_id, $stock, $stock_minimo, $sucursalNombre, $almacenOrigen, $almacenDestino, $inventarioOrigen, $inventarioDestino;

    protected $rules =
    [
        'almacenOrigen' => 'required',
        'almacenDestino' => 'required',
        'producto_id' => 'required',
        'stock' => 'required',
        'stock_minimo' => 'required'
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
        $this->nombreProducto = Inventario::with('productos')->get();


        
    } 

    public function render()
    {           
        $inventarios = Inventario::with('almacenes','productos.categorias')
        ->where('almacen_id' , $this->almacen_id)
        ->paginate($this->paginacion);        

        return view('livewire.inventario.inventario-sucursal',[
            'inventarios' => $inventarios,
            'productos' => Producto::all(),
            'almacenes' => Almacen::all()
            ])
            ->extends('layouts.theme.app')
            ->section('content');              
    }    

    public function Store()
    {  
        $this->validate();

        $inventarios = Inventario::create([
            'almacen_id' => $this->almacen_id,
            'producto_id' => $this->producto_id,
            'usuario_id' => 1,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo          
        ]);
        $this->resetUI();
        $this->emit('item-added', 'Producto Registrado');
    }

    public function Edit(Inventario $inventario)           
    {
       $this->seleccionar_id = $inventario->id;
       $this->almacen_id = $inventario->almacen_id;
       $this->producto_id = $inventario->producto_id;
       $this->usuario_id = $inventario->usuario_id;
       $this->stock = $inventario->stock;
       $this->stock_minimo = $inventario->stock_minimo;    

       $this->emit('modal-show','show modal!');      
        
    }

    public function Update()
    {   
        
        $rules =
    [
        'producto_id' => 'required',
        'stock' => 'required',
        'stock_minimo' => 'required'
    ];

    $messages = [
        'producto_id' => 'El producto es requerido',
        'stock.required' => 'El stock es requerido.',
        'stock_minimo.required' => 'El stock minimo es requerido.',        
    ];
                
        $this->validate($rules,$messages);
        $inventario = Inventario::find($this->seleccionar_id);
        $inventario->update([
            'almacen_id' => $this->almacen_id,
            'producto_id' => $this->producto_id,
            'usuario_id' => 1,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo 
        ]);

        $this->resetUI();
        $this->emit('item-updated', 'Producto Actualizado');

    }

    public function Destroy(Inventario $inventario)
    {        
        $inventario->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Producto Eliminado');

    }

    public function Traslado(Inventario $inventario){
       $this->almacenOrigen = $inventario->almacen_id;
       $this->producto_id = $inventario->producto_id;   
       $this->emit('modal-show-traslado','show modal!');
    }
    
    public function trasladarStock()
    {   
        $rules =
    [
        'almacenOrigen' => 'required',
        'almacenDestino' => 'required',
        'producto_id' => 'required',
        'stock' => 'required',
    ];

        $messages = [

        'almacenDestino.required' => 'El Almacen de destino es requerido',
        'almacenOrigen.required' => 'El Almacen de origen es requerido',        
        'producto_id.required' => 'El producto es requerido',
        'stock.required' => 'El stock es requerido.',        
    ];

        $this->validate($rules,$messages);

        // Validar que el producto exista en el almacen de origen
        $producto = Producto::findOrFail($this->producto_id);
        $inventarioOrigen = Inventario::where('producto_id', $this->producto_id)
                                  ->where('almacen_id', $this->almacenOrigen)
                                  ->first();
        if (!$inventarioOrigen) {            
            $this->reset(['almacenOrigen', 'almacenDestino', 'stock', 'producto_id']);       
            $this->emit('item-traslado', 'Producto Trasladado');            
            $this->addError('producto_id', 'El producto no existe en el almacen de origen.');            
        }

        // Validar que el producto exista en el almacen de destino
        $inventarioDestino = Inventario::where('producto_id', $this->producto_id)
                                            ->where('almacen_id', $this->almacenDestino)
                                            ->first();
        if (!$inventarioDestino->productos->contains('producto_id', $this->producto_id)) { //aca quede
            $this->emit('item-traslado', 'Producto Trasladado');
            $this->addError('producto_id', 'El producto no existe en el almacen de destino.');
            $this->reset(['almacenOrigen', 'almacenDestino', 'stock', 'producto_id']);                
        }          

        // Validar que la sucursal de origen no sea la misma que la de destino
        $almacenOrigen = Almacen::findOrFail($this->almacenOrigen);
        $almacenDestino = Almacen::findOrFail($this->almacenDestino);
        if ($almacenOrigen->id == $almacenDestino->id) {
            $this->reset(['almacenOrigen', 'almacenDestino', 'stock', 'producto_id']);       
            $this->emit('item-traslado', 'Producto Trasladado');
            $this->addError('almacenOrigen', 'El almacen de origen no puede ser el mismo que el de destino.');            
        }        

        // Validar que el stock no sea mayor
        if ($inventarioOrigen->stock < $this->stock) {
        $this->emit('item-traslado', 'Producto Trasladado');
        $this->reset(['almacenOrigen', 'almacenDestino', 'stock', 'producto_id']); 
        $this->addError('sinStock', 'No hay suficiente stock.');                
        }


        // Restar stock del almacen de origen
        $inventarioOrigen->stock -= $this->stock;
        $inventarioOrigen->save();

        // Agregar stock al almacen de destino
        $inventarioDestino->stock += $this->stock;
        $inventarioDestino->save();

        // Redireccionar a la página de inventario
        $this->reset(['almacenOrigen', 'almacenDestino', 'stock', 'producto_id']);       
        $this->emit('item-traslado', 'Producto Trasladado');
    }


    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function resetUI()
    {
       $this->producto_id = " ";
       $this->almacenOrigen = " ";
       $this->almacenDestino = " ";
       $this->stock = " ";
       $this->stock_minimo = " ";             
       $this->seleccionar_id = 0;
       $this->resetValidation();       
        
    }



}
