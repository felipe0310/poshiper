<?php

namespace App\Http\Livewire;

use App\Models\Empresa;
use Livewire\Component;
use App\Models\Almacen;
use Livewire\WithPagination;

class Almacenes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    private $paginacion = 7;
    public $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;

    public $empresa_id, $descripcion, $ubicacion, $entrada, $salida;   

     protected $rules =
    [
        'empresa_id' => 'required',
        'descripcion' => 'required|unique:almacenes,descripcion',
        'ubicacion' => 'required',
        'entrada' => 'required',
        'salida' => 'required'
    ];

    protected $messages = [
        'empresa_id.required' => 'La empresa es requerida.',
        'descripcion.required' => 'La descripcion es requerida.',
        'descripcion.unique' => 'La descripcion ya existe.',         
        'ubicacion.required' => 'La ubicación es requerida.',
        'entrada.required' => 'La hora de entrada en requerida',
        'salida.required' => 'La hora de salida en requerida',        
    ];

    public function mount()
    {
        $this->empresa_id = 'Elegir';
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Almacenes';        
    } 


    public function render()
    {
        if(strlen($this->buscar) > 0)
            $almacenes = Almacen::join('empresas as e','e.id','almacenes.empresa_id')
            ->select('almacenes.*',('e.rut as empresas'))
            ->where('almacenes.descripcion','like','%' . $this->buscar . '%')
            ->orWhere('almacenes.ubicacion','like','%' . $this->buscar . '%')            
            ->orWhere('e.rut','like','%' . $this->buscar . '%')
            ->orderBy('almacenes.descripcion', 'asc')
            ->paginate($this->paginacion);
        else
            $almacenes = Almacen::join('empresas as e','e.id','almacenes.empresa_id')
                ->select('almacenes.*',('e.rut as empresas'))                
                ->orderBy('almacenes.descripcion', 'asc')
                ->paginate($this->paginacion);
            
        return view('livewire.almacen.almacenes',[
            'almacenes'=>$almacenes,
            'empresas' => Empresa::orderBy('rut','asc')->get()
            ])
        ->extends('layouts.theme.app')
        ->section('content');
        $data = Almacen::where('descripcion', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        $data = Almacen::orderBy('descripcion','asc')->paginate($this->paginacion);
    }

    public function Edit($id)           
    {
       $almacen = Almacen::find($id, ['id','empresa_id','descripcion','ubicacion','entrada','salida']);
       $this->seleccionar_id = $almacen->id;
       $this->empresa_id = $almacen->empresa_id;
       $this->descripcion = $almacen->descripcion;
       $this->ubicacion = $almacen->ubicacion;
       $this->entrada = $almacen->entrada;
       $this->salida = $almacen->salida;   

       $this->emit('show-modal','show modal!');      
        
    }

    public function Store()
    {       
        $this->validate();

        $almacen = Almacen::create([
            'empresa_id' => $this->empresa_id,
            'descripcion' => $this->descripcion,
            'ubicacion' => $this->ubicacion,
            'entrada' => $this->entrada,
            'salida' => $this->salida        
        ]);
        $this->resetUI();
        $this->emit('item-added', 'Almacen Registrado');
    }

    public function Update()
    {           
        $rules =
    [
        'empresa_id' => 'required',
        'descripcion' => "required|unique:almacenes,descripcion,{$this->seleccionar_id}",
        'ubicacion' => 'required',
        'entrada' => 'required',
        'salida' => 'required'
    ];

        $messages = [
        'empresa_id.required' => 'La empresa es requerida.',
        'descripcion.required' => 'La descripcion es requerida.',
        'descripcion.unique' => 'La descripcion ya existe.',          
        'ubicacion.required' => 'La ubicación es requerida.',
        'entrada.required' => 'La hora de entrada en requerida',
        'salida.required' => 'La hora de salida en requerida',        
    ];

        $this->validate($rules,$messages);
        $almacen = Almacen::find($this->seleccionar_id);
        $almacen->update([
            'empresa_id' => $this->empresa_id,
            'descripcion' => $this->descripcion,
            'ubicacion' => $this->ubicacion,
            'entrada' => $this->entrada,
            'salida' => $this->salida  
        ]);

        $this->resetUI();
        $this->emit('item-updated', 'Almacen Actualizado');

    }

    public function Destroy(Almacen $almacen)
    {
        
        $almacen->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Almacen Eliminado');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function resetUI()
    {
       
       $this->empresa_id = " ";       
       $this->descripcion = " ";
       $this->ubicacion = " ";
       $this->entrada = " ";
       $this->salida = " ";
       $this->buscar = " ";
       $this->seleccionar_id = 0;       
        
    }



}

