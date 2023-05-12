<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Almacen;
use App\Models\Inventario;
use Livewire\WithPagination;

class InventarioSucursal extends Component
{
    use WithPagination;
    

    protected $paginationTheme = 'bootstrap';
    public $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;
    private $paginacion = 7;
    
    public function mount()
    {        
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Inventarios';            
                
    } 

    public function render()
    {   
        $inventarios = Inventario::with('productos.categorias', 'almacenes')->get();          

        return view('livewire.inventario.inventarios',[
            'inventarios' => $inventarios,
            'almacenes' => Almacen::orderBy('descripcion','asc')->get(),
                                   
            ])
            ->extends('layouts.theme.app')
            ->section('content');        
    }   

    public function verSucursal($id)
    {              
        return redirect('inventario/'.$id);
    }   

}
