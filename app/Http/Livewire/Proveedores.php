<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Proveedor;
use Livewire\WithPagination;

class Proveedores extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;
    private $paginacion = 7;

    public $nombre, $direccion, $telefono, $email;
    

    protected $rules = [
            'nombre' => 'required|unique:proveedores',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required'
        ];

    protected $messages = [
            'nombre.required' => 'El nombre del proveedor es requerido.',
            'nombre.unique' => 'El nombre del proveedor ya existe.',
            'direccion.required' => 'La dirección del proveedor es requerida.',
            'telefono.required' => 'El teléfono del proveedor es requerido.',
            'email.required' => 'El email del proveedor es requerido.',
            
        ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Proveedores';
    }    

    public function render()
    {
        if(strlen($this->buscar) > 0)
            $data = Proveedor::where('nombre', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        else
            $data = Proveedor::orderBy('nombre','asc')->paginate($this->paginacion);

        return view('livewire.proveedor.proveedores',['proveedores'=>$data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit($id)           
    {
       $proveedor = Proveedor::find($id, ['id','nombre','direccion','telefono','email']);
       $this->seleccionar_id = $proveedor->id;
       $this->nombre = $proveedor->nombre;
       $this->direccion = $proveedor->direccion;
       $this->telefono = $proveedor->telefono;
       $this->email = $proveedor->email;       

       $this->emit('show-modal','show modal!');   
    }

    public function Store()
    {       
        $this->validate();

        $proveedor = Proveedor::create([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email
        ]);

        $this->resetUI();
        $this->emit('item-added', 'Prooveedor Registrado');
    }

    public function Update()
    {

        $rules = [
            'nombre' => "required|unique:proveedores,nombre,{$this->seleccionar_id}",
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required'
        ];

        $messages = [
            'nombre.required' => 'El nombre del proveedor es requerido.',
            'nombre.unique' => 'El nombre del proveedor ya existe.',
            'direccion.required' => 'La dirección del proveedor es requerida.',
            'telefono.required' => 'El teléfono del proveedor es requerido.',
            'email.required' => 'El email del proveedor es requerido.',
            
        ];        

        $this->validate($rules,$messages);
        $proveedor = Proveedor::find($this->seleccionar_id);
        $proveedor->update([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email
        ]);
        $this->resetUI();
        $this->emit('item-updated', 'Proveedor Actualizado');

    }

    public function Destroy(Proveedor $proveedor)
    {
        //$categoria = Categoria::find($id);
        $categoria->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Proveedor Eliminado');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function resetUI()
    {
        $this->buscar =  '';
        $this->seleccionar_id = 0;

        $this->nombre = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->email = '';       
        
    }



}
