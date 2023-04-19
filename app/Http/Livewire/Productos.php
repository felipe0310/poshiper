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
        'codigo_barras.unique' => 'El codigo ya existe',
        'codigo_barras.max' => 'El codigo debe ser maximo 15 digitos',
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
    } 


    public function render()
    {
        if(strlen($this->buscar) > 0)
            $productos = '';
        else
            
        return view('livewire.producto.productos',['productos'=>$data])
        ->extends('layouts.theme.app')
        ->section('content');
        $data = Producto::where('descripcion', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        $data = Producto::orderBy('descripcion','asc')->paginate($this->paginacion);
    }
}
