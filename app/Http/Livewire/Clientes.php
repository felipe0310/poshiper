<?php

namespace App\Http\Livewire;

use App\Models\Cliente;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Clientes extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $buscar;

    public $seleccionar_id;

    public $paginaTitulo;

    public $nombreComponente;

    private $paginacion = 7;

    public $rut;

    public $nombre;

    public $apellido;

    public $direccion;

    public $limite_credito;

    public $credito_usado;

    public $telefono;

    public $descuento;

    protected $rules = [
        'rut' => 'required|unique:clientes',
        'nombre' => 'required|unique:clientes',
        'apellido' => 'required',
        'direccion' => 'required',
        'telefono' => 'required',
        'descuento' => 'max:1',
    ];

    protected $messages = [
        'rut.required' => 'El rut del cliente es requerido.',
        'rut.unique' => 'El rut del cliente ya existe.',
        'nombre.required' => 'El nombre del cliente es requerido.',
        'nombre.unique' => 'El nombre del cliente ya existe.',
        'direccion.required' => 'La dirección del cliente es requerida.',
        'telefono.required' => 'El teléfono del cliente es requerido.',
        'descuento.max' => 'El descuento debe ser 5.',
    ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Clientes';

    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (strlen($this->buscar) > 0) {
            $data = Cliente::where('nombre', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        } else {
            $data = Cliente::orderBy('nombre', 'asc')->paginate($this->paginacion);
        }

        return view('livewire.cliente.clientes', ['clientes' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Edit($id)
    {
        $cliente = Cliente::find($id, ['id', 'nombre', 'direccion', 'telefono', 'limite_credito', 'rut', 'apellido', 'descuento']);
        $this->seleccionar_id = $cliente->id;
        $this->rut = $cliente->rut;
        $this->nombre = $cliente->nombre;
        $this->apellido = $cliente->apellido;
        $this->direccion = $cliente->direccion;
        $this->telefono = $cliente->telefono;
        $this->limite_credito = $cliente->limite_credito;
        $this->descuento = $cliente->descuento;

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $this->validate();

        $Cliente = Cliente::create([
            'rut' => $this->rut,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'limite_credito' => $this->limite_credito,
            'descuento' => $this->descuento,

        ]);

        $this->resetUI();
        $this->emit('item-added', 'Cliente Registrado');
        $this->alert('success', 'CLIENTE CREADO CON EXITO', [
            'position' => 'center',
        ]);
    }

    public function Update()
    {

        $rules = [
            'rut' => "required|unique:clientes,rut,{$this->seleccionar_id}",
            'nombre' => "required|unique:clientes,nombre,{$this->seleccionar_id}",
            'apellido' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'descuento' => 'max:1',
        ];

        $messages = [
            'rut.required' => 'El rut del cliente es requerido.',
            'rut.unique' => 'El rut del cliente ya existe.',
            'nombre.required' => 'El nombre del cliente es requerido.',
            'nombre.unique' => 'El nombre del cliente ya existe.',
            'direccion.required' => 'La dirección del cliente es requerida.',
            'telefono.required' => 'El teléfono del cliente es requerido.',
            'descuento.max' => 'El descuento debe ser 5.',
        ];

        $this->validate($rules, $messages);
        $cliente = Cliente::find($this->seleccionar_id);
        $cliente->update([
            'rut' => $this->rut,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'limite_credito' => $this->limite_credito,
            'descuento' => $this->descuento,
        ]);
        $this->resetUI();
        $this->emit('item-updated', 'Cliente Actualizado');
        $this->alert('success', 'CLIENTE ACTUALIZADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    public function Destroy(Cliente $cliente)
    {
        //$categoria = Categoria::find($id);
        $cliente->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Cliente Eliminado');
        $this->alert('success', 'CLIENTE ELIMINADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function resetUI()
    {
        $this->buscar = '';
        $this->seleccionar_id = 0;
        $this->resetValidation();

        $this->rut = '';
        $this->nombre = '';
        $this->apellido = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->limite_credito = '';
        $this->descuento = '';

    }
}
