<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;

class Categorias extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $nombre, $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;
    private $paginacion = 7;

    protected $rules = [
            'nombre' => 'required|unique:categorias|min:3'
        ];

    protected $messages = [
            'nombre.required' => 'El nombre de la categoría es requerido.',
            'nombre.unique' => 'El nombre de la categoría ya existe.',
            'nombre.min' => 'El nombre de la categoría debe tener mínimo 3 caracteres.',
        ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Categorías';
    }    

    public function render()
    {
        if(strlen($this->buscar) > 0)
            $data = Categoria::where('nombre', 'like', '%'.$this->buscar.'%')->paginate($this->paginacion);
        else
            $data = Categoria::orderBy('nombre','asc')->paginate($this->paginacion);

        return view('livewire.categoria.categorias',['categorias'=>$data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit($id)           
    {
       $categoria = Categoria::find($id, ['id','nombre']);
       $this->nombre = $categoria->nombre;
       $this->seleccionar_id = $categoria->id;

       $this->emit('show-modal','show modal!');   
    }

    public function Store()
    {       
        $this->validate();

        $categoria = Categoria::create([
            'nombre' => $this->nombre
        ]);

        $this->resetUI();
        $this->emit('item-added', 'Categoria Registrada');
    }

    public function Update()
    {
        $this->validate();
        $categoria = Categoria::find($this->seleccionar_id);
        $categoria->update([
            'nombre' => $this->nombre
        ]);
        $this->resetUI();
        $this->emit('item-updated', 'Categoria Actualizada');

    }

    public function Destroy(Categoria $categoria)
    {
        //$categoria = Categoria::find($id);
        $categoria->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Categoria Eliminada');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function resetUI()
    {
        $this->nombre = '';
        $this->buscar =  '';
        $this->seleccionar_id = 0;
        
    }



}
