<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\WithPagination;

class Productos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $descripcion, $codigo_barras, $precio_compra, $precio_venta, $precio_mayoreo, $precio_oferta, $categoria_id, $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;
    
    private $paginacion = 7;    

     protected $rules =
    [
        'categoria_id' => 'required',
        'descripcion' => 'required',
        'codigo_barras' => 'required|unique:productos|max:15',
        'precio_compra' => 'required',
        'precio_venta' => 'required',
        'precio_oferta' => 'required',
        'precio_mayoreo' => 'required',

    ];

    protected $messages = [
        'categoria_id.required' => 'La categoria es requerida.',
        'descripcion.required' => 'La descripcion es requerida.',
        'descripcion.unique' => 'La Categoria ya existe',
        'codigo_barras.required' => 'El codigo de barras es requerido.',
        'codigo_barras.unique' => 'El codigo de barras ya existe',
        'codigo_barras.max' => 'El codigo de barras debe ser maximo 15 digitos',
        'precio_compra.required' => 'El precio de compra es requerido.',
        'precio_venta.required' => 'El precio de ventas es requerido.',
        'precio_oferta.required' => 'El precio oferta es requerido, si no mantiene ingresar 0.',
        'precio_mayoreo.required' => 'El precio mayoreo es requerido, si no mantiene ingresar 0.',
    ];



    public function mount()
    {
        $this->categoria_id = 'Elegir';
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Productos';
        $this->precio_compra = "0";
        $this->precio_venta = "0";
        $this->precio_mayoreo = "0";
        $this->precio_oferta = "0";
    } 


    public function render()
    {
        if(strlen($this->buscar) > 0)
            $productos = Producto::join('categorias as c','c.id','productos.categoria_id')
            ->select('productos.*',('c.nombre as categorias'))
            ->where('productos.descripcion','like','%' . $this->buscar . '%')
            ->orWhere('productos.codigo_barras','like','%' . $this->buscar . '%')
            ->orWhere('c.nombre','like','%' . $this->buscar . '%')
            ->orderBy('productos.descripcion', 'asc')
            ->paginate($this->paginacion);
        else
            $productos = Producto::join('categorias as c','c.id','productos.categoria_id')
                ->select('productos.*',('c.nombre as categorias'))                
                ->orderBy('productos.descripcion', 'asc')
                ->paginate($this->paginacion);
            
        return view('livewire.producto.productos',[
            'productos'=>$productos,
            'categorias' => Categoria::orderBy('nombre','asc')->get()
            ])
        ->extends('layouts.theme.app')
        ->section('content');
        $data = Producto::where('descripcion', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        $data = Producto::orderBy('descripcion','asc')->paginate($this->paginacion);
    }

    public function Edit($id)           
    {
       $producto = Producto::find($id, ['id','categoria_id','descripcion','codigo_barras','precio_compra','precio_venta','precio_mayoreo','precio_venta','precio_oferta']);
       $this->seleccionar_id = $producto->id;
       $this->categoria_id = $producto->categoria_id;
       $this->descripcion = $producto->descripcion;
       $this->codigo_barras = $producto->codigo_barras;
       $this->precio_compra = $producto->precio_compra;
       $this->precio_venta = $producto->precio_venta;
       $this->precio_mayoreo = $producto->precio_mayoreo;
       $this->precio_oferta = $producto->precio_oferta;     

       $this->emit('show-modal','show modal!');      
        
    }

    public function Store()
    {       
        $this->validate();

        $producto = Producto::create([
            'categoria_id' => $this->categoria_id,
            'descripcion' => $this->descripcion,
            'codigo_barras' => $this->codigo_barras,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'precio_mayoreo' => $this->precio_mayoreo,
            'precio_oferta' => $this->precio_oferta            
        ]);
        $this->resetUI();
        $this->emit('item-added', 'Producto Registrado');
    }

    public function Update()
    {   
        
        $rules =
    [
        'categoria_id' => 'required',
        'descripcion' => 'required',
        'codigo_barras' => "required|unique:productos,codigo_barras,{$this->seleccionar_id}|max:15",
        'precio_compra' => 'required',
        'precio_venta' => 'required',
        'precio_oferta' => 'required',
        'precio_mayoreo' => 'required',

    ];

        $messages = [
        'categoria_id.required' => 'La categoria es requerida.',
        'descripcion.required' => 'La descripcion es requerida.',
        'descripcion.unique' => 'La Categoria ya existe',
        'codigo_barras.required' => 'El codigo de barras es requerido.',
        'codigo_barras.unique' => 'El codigo de barras ya existe',
        'codigo_barras.max' => 'El codigo de barras debe ser maximo 15 digitos',
        'precio_compra.required' => 'El precio de compra es requerido.',
        'precio_venta.required' => 'El precio de ventas es requerido.',
        'precio_oferta.required' => 'El precio oferta es requerido, si no mantiene ingresar 0.',
        'precio_mayoreo.required' => 'El precio mayoreo es requerido, si no mantiene ingresar 0.',
    ];
                
        $this->validate($rules,$messages);
        $producto = Producto::find($this->seleccionar_id);
        $producto->update([
            'categoria_id' => $this->categoria_id,
            'descripcion' => $this->descripcion,
            'codigo_barras' => $this->codigo_barras,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'precio_mayoreo' => $this->precio_mayoreo,
            'precio_oferta' => $this->precio_oferta 
        ]);

        $this->resetUI();
        $this->emit('item-updated', 'Producto Actualizado');

    }

    public function Destroy(Producto $producto)
    {
        //$categoria = Categoria::find($id);
        $producto->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Producto Eliminado');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function resetUI()
    {
       $this->producto_id = " ";
       $this->categoria_id = " ";
       $this->descripcion = " ";
       $this->codigo_barras = " ";
       $this->precio_compra = "0";
       $this->precio_venta = "0";
       $this->precio_mayoreo = "0";
       $this->precio_oferta = "0";
       $this->seleccionar_id = 0;       
        
    }



}
