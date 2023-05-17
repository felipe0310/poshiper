<?php

namespace App\Http\Livewire;

use App\Models\Producto;
use App\Models\Proveedor;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Proveedores extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $buscar;

    public $seleccionar_id;

    public $paginaTitulo;

    public $nombreComponente;

    private $paginacion = 7;

    public $nombre;

    protected $rules = [
        'nombre' => 'required|unique:proveedores',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre del proveedor es requerido.',
        'nombre.unique' => 'El nombre del proveedor ya existe.',

    ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Proveedores';
    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (strlen($this->buscar) > 0) {
            $data = Proveedor::where('nombre', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        } else {
            $data = Proveedor::orderBy('nombre', 'asc')->paginate($this->paginacion);
        }

        return view('livewire.proveedor.proveedores', ['proveedores' => $data, 'productos' => Producto::all()])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Edit($id)
    {
        $proveedor = Proveedor::find($id, ['id', 'nombre']);
        $this->seleccionar_id = $proveedor->id;
        $this->nombre = $proveedor->nombre;

        $this->emit('show-modal', 'show modal!');
    }

    public function Store()
    {
        $this->validate();

        $proveedor = Proveedor::create([
            'nombre' => $this->nombre,
        ]);

        $this->resetUI();
        $this->emit('item-added', 'Prooveedor Registrado');
        $this->alert('success', 'PROVEEDOR CREADO CON EXITO', [
            'position' => 'center',
        ]);
    }

    public function Update()
    {

        $rules = [
            'nombre' => "required|unique:proveedores,nombre,{$this->seleccionar_id}",
        ];

        $messages = [
            'nombre.required' => 'El nombre del proveedor es requerido.',
            'nombre.unique' => 'El nombre del proveedor ya existe.',

        ];

        $this->validate($rules, $messages);
        $proveedor = Proveedor::find($this->seleccionar_id);
        $proveedor->update([
            'nombre' => $this->nombre,
        ]);
        $this->resetUI();
        $this->emit('item-updated', 'Proveedor Actualizado');
        $this->alert('success', 'PROVEEDOR ACTUALIZADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    public function Destroy(Proveedor $proveedor)
    {
        //$categoria = Categoria::find($id);
        $proveedor->delete();
        $this->resetUI();
        $this->alert('success', 'PROVEEDOR ELIMINADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function resetUI()
    {
        $this->seleccionar_id = 0;
        $this->resetValidation();
        $this->nombre = '';

    }
}
