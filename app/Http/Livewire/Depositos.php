<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Deposito;
use Livewire\WithPagination;

class Depositos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;

    public $producto_id, $stock;      

    private $paginacion = 7;    

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
        $this->stock = "0";
    } 

    public function render()
    {   
        if(strlen($this->buscar) > 0)
            $depositos = Deposito::join('productos as p','p.id','depositos.producto_id')
            ->select('depositos.*','p.descripcion as productos_descripcion','p.codigo_barras as productos_codigo')
            ->where('p.descripcion','like','%' . $this->buscar . '%')
            ->orWhere('p.codigo_barras','like','%' . $this->buscar . '%')            
            ->orderBy('p.descripcion', 'asc')
            ->paginate($this->paginacion);
        else
            $depositos = Deposito::join('productos as p','p.id','depositos.producto_id')
                ->select('depositos.*','p.descripcion as productos_descripcion','p.codigo_barras as productos_codigo')                
                ->orderBy('p.descripcion', 'asc')
                ->paginate($this->paginacion);
            
        return view('livewire.deposito.depositos',[            
            'depositos'=>$depositos,
            'productos' => Producto::orderBy('descripcion','asc')->get()            
        ])
        ->extends('layouts.theme.app')
        ->section('content');   
           
    }

    public function Edit(Deposito $deposito)
    {       
       $this->seleccionar_id = $deposito->id;
       $this->producto_id = $deposito->producto_id;
       $this->stock = $deposito->stock;           

       $this->emit('modal-show','show modal!');        
        
    }   

    public function Store()
    {   
        $this->validate();  
        $deposito = Deposito::create([
            'producto_id' => $this->producto_id,
            'stock' => $this->stock,         
        ]);
        $this->resetUI();
        $this->emit('item-added', 'Deposito Registrado');
    }

    public function Update()
    {             
        $rules =
    [
        'producto_id' => "required|unique:depositos,producto_id,{$this->seleccionar_id}",
        'stock' => 'required',

    ];
        $messages = [
        'producto_id.required' => 'El producto es requerido.',
        'stock.required' => 'El Stock no puede ser vacio, sin Stock ingrese 0.',
        'producto_id.unique' => 'El producto ya existe',
    ];
        

        $this->validate($rules,$messages);
        $deposito = Deposito::find($this->seleccionar_id);
        $deposito->update([
            'producto_id' => $this->producto_id,
            'stock' => $this->stock,     
        ]);

        $this->resetUI();
        $this->emit('item-updated', 'Deposito Actualizado');

    }    

    public function Destroy(Deposito $deposito)
    {
        $deposito->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Deposito Eliminado');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function resetUI()
    {         
       $this->resetValidation();
       $this->producto_id = " ";
       $this->stock = "0";       
       $this->seleccionar_id = 0;       
    }



}
