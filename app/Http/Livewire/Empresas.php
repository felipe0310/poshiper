<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Empresa;
use Livewire\WithPagination;

class Empresas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public  $buscar, $paginaTitulo, $nombreComponente, $seleccionar_id;
    private $paginacion = 7; 

    public $rut, $razon_social, $direccion, $email, $iva;

     protected $rules =
    [
        'rut' => 'required|unique:empresas',
        'razon_social' => 'required',
        'direccion' => 'required',
        'email' => 'required',
        'iva' => 'required',
    ];

    protected $messages = [
        'rut.required' => 'El RUT de la empresa es requerido.',
        'razon_social.required' => 'La Razón Social es requerida.',
        'rut.unique' => 'El RUT de la empresa ya existe',
        'direccion.required' => 'La dirección es requerida.',
        'email.unique' => 'El email es requerido',
        'iva.max' => 'El I.V.A es requerido',        
    ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Empresas';        
    } 


    public function render()
    {
        if(strlen($this->buscar) > 0)
            $data = Empresa::where('rut', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        else
            $data = Empresa::orderBy('rut','asc')->paginate($this->paginacion);

        return view('livewire.empresa.empresas',['empresas'=>$data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit($id)           
    {
       $empresa = Empresa::find($id, ['id','rut','razon_social','direccion','email','iva']);
       $this->seleccionar_id = $empresa ->id;
       $this->rut = $empresa ->rut;
       $this->razon_social = $empresa ->razon_social;
       $this->direccion = $empresa ->direccion;
       $this->email = $empresa ->email;
       $this->iva = $empresa ->iva;        

       $this->emit('show-modal','show modal!');      
        
    }

    public function Store()
    {       
        $this->validate();

        $producto = Empresa::create([            
            'rut' => $this->rut,
            'razon_social' => $this->razon_social,
            'direccion' => $this->direccion,
            'email' => $this->email,
            'iva' => $this->iva                      
        ]);
        $this->resetUI();
        $this->emit('item-added', 'Empresa Registrada');
    }

    public function Update()
    {   
        
        $rules =
    [        
        'rut' => "required|unique:empresas,rut,{$this->seleccionar_id}",
        'razon_social' => 'required',
        'direccion' => 'required',
        'email' => 'required',
        'iva' => 'required',

    ];

        $messages = [
        'rut.required' => 'El RUT de la empresa es requerido.',
        'rut.unique' => 'El RUT de la empresa ya existe',
        'razon_social.required' => 'La Razón Social es requerida.',        
        'direccion.required' => 'La dirección es requerida.',
        'email.unique' => 'El email es requerido',
        'iva.max' => 'El I.V.A es requerido',        
    ];
                
        $this->validate($rules,$messages);
        $empresa = Empresa::find($this->seleccionar_id);
        $empresa->update([
            'rut' => $this->rut,
            'razon_social' => $this->razon_social,
            'direccion' => $this->direccion,
            'email' => $this->email,
            'iva' => $this->iva 
        ]);

        $this->resetUI();
        $this->emit('item-updated', 'Empresa Actualizada');

    }

    public function Destroy(Empresa $empresa)
    {
        //$categoria = Categoria::find($id);
        $empresa->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Empresa Eliminada');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function resetUI()
    {
       $this->rut = " ";
       $this->razon_social = " ";
       $this->direccion = " ";
       $this->email = " ";
       $this->iva = " ";              
       $this->seleccionar_id = 0;
       $this->resetValidation();       
        
    }



}

