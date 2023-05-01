<?php

namespace App\Http\Livewire;

use App\Models\Almacen;
use Livewire\Component;
use App\Models\DocAlmacen;
use Livewire\WithPagination;

class DocAlmacenes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $buscar, $seleccionar_id, $paginaTitulo, $nombreComponente;
    private $paginacion = 7;

    public $almacen_id, $documento, $serie, $cantidad;    

    protected $rules = [
            'almacen_id' => 'required',
            'documento' => 'required',
            'serie' => 'required',
            'cantidad' => 'required|max:8',
        ];

    protected $messages = [
            'almacen_id.required' => 'Seleccione un almacén.',
            'documento.required' => 'El nombre del documento es requerido',            
            'serie.required' => 'El la serie para el documento es requerida',            
            'cantidad.required' => 'La cantidad para el documento es requerida.',            
            'cantidad.max' => 'La cantidad maxima es de 8 digitos.',
        ];

    public function mount()
    {
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Doc. Almacenes';
    }    

    public function render()
    {
        if(strlen($this->buscar) > 0)
            $docalmacenes = DocAlmacen::join('almacenes as a','a.id','doc_almacenes.almacen_id')
            ->select('doc_almacenes.*',('a.descripcion as almacenes'))
            ->where('doc_almacenes.documento','like','%' . $this->buscar . '%')
            ->orWhere('doc_almacenes.serie','like','%' . $this->buscar . '%')
            ->orWhere('c.descripcion','like','%' . $this->buscar . '%')
            ->orderBy('a.descripcion', 'asc')
            ->paginate($this->paginacion);
        else
            $docalmacenes = DocAlmacen::join('almacenes as a','a.id','doc_almacenes.almacen_id')
            ->select('doc_almacenes.*',('a.descripcion as almacenes'))            
            ->orderBy('a.descripcion', 'asc')
            ->paginate($this->paginacion);
            
        return view('livewire.docalmacen.docalmacenes',[
            'docalmacenes'=>$docalmacenes,
            'almacenes' => Almacen::orderBy('descripcion','asc')->get()
            ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit(DocAlmacen $doc_almacenes)           
    {
       $this->seleccionar_id = $doc_almacenes->id;
       $this->almacen_id = $doc_almacenes->almacen_id;
       $this->documento = $doc_almacenes->documento;
       $this->serie = $doc_almacenes->serie;
       $this->cantidad = $doc_almacenes->cantidad;       

       $this->emit('modal-show','modal show!');   
    }

    public function Store()
    {       
        $this->validate();

        $docalmacen = DocAlmacen::create([
            'almacen_id' => $this->almacen_id,
            'documento' => $this->documento,
            'serie' => $this->serie,
            'cantidad' => $this->cantidad,
        ]);

        $this->resetUI();
        $this->emit('item-added', 'Documento Registrado');
    }

    public function Update()
    {
        $rules = [
            'almacen_id' => 'required',
            'documento' => "required",
            'serie' => "required",
            'cantidad' => "required|max:8",
        ];

        $messages = [
            'almacen_id.required' => 'Seleccione un almacén.',
            'documento.required' => 'El nombre del documento es requerido',            
            'serie.required' => 'El la serie para el documento es requerida',            
            'cantidad.required' => 'La cantidad para el documento es requerida.',            
            'cantidad.max' => 'La cantidad maxima es de 8 digitos.',
        ];

        $this->validate($rules,$messages);
        $docalmacen = DocAlmacen::find($this->seleccionar_id);
        $docalmacen->update([
            'almacen_id' => $this->almacen_id,
            'documento' => $this->documento,
            'serie' => $this->serie,
            'cantidad' => $this->cantidad,
        ]);
        $this->resetUI();
        $this->emit('item-updated', 'Documento Actualizado');

    }

    public function Destroy(DocAlmacen $docalmacen)
    {
        
        $docalmacen->delete();
        $this->resetUI();
        $this->emit('item-delete', 'Documento Eliminado');

    }

    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    public function resetUI()
    {
        $this->almacen_id = '';
        $this->documento =  '';
        $this->serie =  '';
        $this->cantidad =  '';
        $this->seleccionar_id = 0;
        $this->resetValidation();
        
    }



}
