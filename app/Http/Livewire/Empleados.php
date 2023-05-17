<?php

namespace App\Http\Livewire;

use App\Models\Empleado;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Empleados extends Component
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

    public $nombres;

    public $apellidos;

    public $telefono;

    public $direccion;

    public $email;

    protected $rules = [
        'rut' => 'required|unique:empleados|max:12',
        'nombres' => 'required',
        'apellidos' => 'required',
        'telefono' => 'required',
        'direccion' => 'required',
        'email' => 'required',
    ];

    protected $messages = [
        'rut.required' => 'El rut es requerido.',
        'rut.unique' => 'El rut ya existe.',
        'rut.max' => 'El rut debe contener maximo 12 caracteres.',
        'nombres.required' => 'Los nombres son requeridos.',
        'apellidos.required' => 'Los apellidos son requeridos.',
        'telefono.required' => 'El telefono es requerido.',
        'direccion.required' => 'La direccion es requerida.',
        'email.required' => 'El email es requerido.',
    ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Empleados';
    }

    public function render()
    {
        if (strlen($this->buscar) > 0) {
            $data = Empleado::where('nombres', 'like', '%'.$this->buscar.'%')
                ->orWhere('apellidos', 'like', '%'.$this->buscar.'%')
                ->orWhere('rut', 'like', '%'.$this->buscar.'%')
                ->paginate($this->paginacion);
        } else {
            $data = Empleado::orderBy('nombres', 'asc')->paginate($this->paginacion);
        }

        return view('livewire.empleado.empleados', ['empleados' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Edit(Empleado $empleados)
    {
        $this->seleccionar_id = $empleados->id;
        $this->rut = $empleados->rut;
        $this->nombres = $empleados->nombres;
        $this->apellidos = $empleados->apellidos;
        $this->telefono = $empleados->telefono;
        $this->direccion = $empleados->direccion;
        $this->email = $empleados->email;

        $this->emit('modal-show', 'modal show!');
    }

    public function Store()
    {
        $this->validate();

        $empleado = Empleado::create([
            'rut' => $this->rut,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'email' => $this->email,
        ]);

        $this->resetUI();
        $this->emit('item-added', 'Empleado Registrado');
    }

    public function Update()
    {
        $rules = [
            'rut' => "required|unique:empleados,rut,{$this->seleccionar_id}|max:12",
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'email' => 'required',
        ];

        $messages = [
            'rut.required' => 'El rut es requerido.',
            'rut.unique' => 'El rut ya existe.',
            'rut.max' => 'El rut debe contener maximo 12 caracteres.',
            'nombres.required' => 'Los nombres son requeridos.',
            'apellidos.required' => 'Los apellidos son requeridos.',
            'telefono.required' => 'El telefono es requerido.',
            'direccion.required' => 'La direccion es requerida.',
            'email.required' => 'El email es requerido.',
        ];

        $this->validate($rules, $messages);
        $empleado = Empleado::find($this->seleccionar_id);
        $empleado->update([
            'rut' => $this->rut,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'email' => $this->email,
        ]);
        $this->resetUI();
        $this->emit('item-updated', 'Empleado Actualizado');

    }

    public function Destroy(Empleado $empleado)
    {

        $empleado->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Empleado Eliminado');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function resetUI()
    {

        $this->rut = '';
        $this->nombres = '';
        $this->apellidos = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->email = '';
        $this->seleccionar_id = 0;
        $this->resetValidation();

    }
}
