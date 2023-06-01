<?php

namespace App\Http\Livewire;

use App\Exports\ProductosExport;
use App\Imports\ProductosImport;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Productos extends Component
{
    use WithPagination;
    use LivewireAlert;
    use WithFileUploads;

    public $excel;

    protected $paginationTheme = 'bootstrap';

    public $descripcion;

    public $codigo_barras;

    public $precio_compra;

    public $precio_venta;

    public $precio_mayoreo;

    public $precio_oferta;

    public $categoria_id;

    public $buscar;

    public $seleccionar_id;

    public $paginaTitulo;

    public $nombreComponente;

    public $proveedor_id;

    public $cantidad_caja;

    private $paginacion = 15;

    protected $rules =
    [
        'categoria_id' => 'required',
        'descripcion' => 'required',
        'codigo_barras' => 'required|unique:productos|max:15',
        'precio_compra' => 'required',
        'precio_venta' => 'required',
        'precio_oferta' => 'required',
        'precio_mayoreo' => 'required',
        'cantidad_caja' => 'required',

    ];

    protected $messages = [
        'categoria_id.required' => 'La categoria es requerida.',
        'descripcion.required' => 'La descripcion es requerida.',
        'descripcion.unique' => 'La Categoria ya existe',
        'codigo_barras.required' => 'El codigo de barras es requerido.',
        'codigo_barras.unique' => 'El codigo de barras ya existe',
        'codigo_barras.max' => 'El codigo de barras debe ser maximo 15 digitos',
        'precio_compra.required' => 'El precio de compra es requerido.',
        'precio_venta.required' => 'El precio de ventas es requerido.',
        'precio_oferta.required' => 'El precio oferta es requerido, si no mantiene ingresar 0.',
        'precio_mayoreo.required' => 'El precio mayoreo es requerido, si no mantiene ingresar 0.',
        'cantidad_caja.required' => 'La cantidad de manga o caja es requerida.',
    ];

    public function mount()
    {
        $this->categoria_id = 'Elegir';
        $this->paginaTitulo = 'Listado';
        $this->nombreComponente = 'Productos';
        $this->codigo_barras = 0;
        $this->precio_compra = 0;
        $this->precio_venta = 0;
        $this->precio_mayoreo = 0;
        $this->precio_oferta = 0;
        $this->cantidad_caja = 0;
    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (strlen($this->buscar) > 2) {
            $productos = Producto::join('categorias as c', 'c.id', 'productos.categoria_id')
                ->join('proveedores as pr', 'pr.id', 'productos.proveedor_id')
                ->select('productos.*', 'c.nombre as categorias', 'pr.nombre as proveedores')
                ->where('productos.descripcion', 'like', '%'.$this->buscar.'%')
                ->orWhere('productos.codigo_barras', 'like', '%'.$this->buscar.'%')
                ->orWhere('c.nombre', 'like', '%'.$this->buscar.'%')
                ->orderBy('productos.descripcion', 'asc')
                ->paginate($this->paginacion);
        } else {
            $productos = Producto::join('categorias as c', 'c.id', 'productos.categoria_id')
                ->join('proveedores as pr', 'pr.id', 'productos.proveedor_id')
                ->select('productos.*', 'c.nombre as categorias', 'pr.nombre as proveedores')
                ->orderBy('productos.descripcion', 'asc')
                ->paginate($this->paginacion);
        }

        return view('livewire.producto.productos', [
            'productos' => $productos,
            'categorias' => Categoria::orderBy('nombre', 'asc')->get(),
            'proveedores' => Proveedor::all(),
        ])
            ->extends('layouts.theme.app')
            ->section('content');

    }

    public function Edit(Producto $producto)
    {
        $this->seleccionar_id = $producto->id;
        $this->categoria_id = $producto->categoria_id;
        $this->descripcion = $producto->descripcion;
        $this->codigo_barras = $producto->codigo_barras;
        $this->precio_compra = $producto->precio_compra;
        $this->precio_venta = $producto->precio_venta;
        $this->precio_mayoreo = $producto->precio_mayoreo;
        $this->precio_oferta = $producto->precio_oferta;
        $this->proveedor_id = $producto->proveedor_id;
        $this->cantidad_caja = $producto->cantidad_caja;

        $this->emit('modal-show', 'show modal!');

    }

    public function Store()
    {
        $this->validate();

        $producto = Producto::create([
            'categoria_id' => $this->categoria_id,
            'descripcion' => $this->descripcion,
            'codigo_barras' => $this->codigo_barras,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'precio_mayoreo' => $this->precio_mayoreo,
            'precio_oferta' => $this->precio_oferta,
            'proveedor_id' => $this->proveedor_id,
            'cantidad_caja' => $this->cantidad_caja,
        ]);
        $this->resetUI();
        $this->emit('item-updated', 'PRODUCTO ACTUALIZADO');
        $this->alert('success', 'PRODUCTO CREADO CON EXITO', [
            'position' => 'center',
        ]);
    }

    public function Update()
    {

        $rules =
    [
        'categoria_id' => 'required',
        'descripcion' => 'required',
        'codigo_barras' => "required|unique:productos,codigo_barras,{$this->seleccionar_id}|max:15",
        'precio_compra' => 'required',
        'precio_venta' => 'required',
        'precio_oferta' => 'required',
        'precio_mayoreo' => 'required',
        'proveedor_id' => 'required',
        'cantidad_caja' => 'required',

    ];

        $messages = [
            'categoria_id.required' => 'La categoria es requerida.',
            'descripcion.required' => 'La descripcion es requerida.',
            'descripcion.unique' => 'La Categoria ya existe',
            'codigo_barras.required' => 'El codigo de barras es requerido.',
            'codigo_barras.unique' => 'El codigo de barras ya existe',
            'codigo_barras.max' => 'El codigo de barras debe ser maximo 15 digitos',
            'precio_compra.required' => 'El precio de compra es requerido.',
            'precio_venta.required' => 'El precio de ventas es requerido.',
            'precio_oferta.required' => 'El precio oferta es requerido, si no mantiene ingresar 0.',
            'precio_mayoreo.required' => 'El precio mayoreo es requerido, si no mantiene ingresar 0.',
            'proveedor_id.required' => 'El proveedor es requerida.',
            'cantidad_caja.required' => 'La cantidad de manga o caja es requerida.',
        ];

        $this->validate($rules, $messages);
        $producto = Producto::find($this->seleccionar_id);
        $producto->update([
            'categoria_id' => $this->categoria_id,
            'descripcion' => $this->descripcion,
            'codigo_barras' => $this->codigo_barras,
            'precio_compra' => $this->precio_compra,
            'precio_venta' => $this->precio_venta,
            'precio_mayoreo' => $this->precio_mayoreo,
            'precio_oferta' => $this->precio_oferta,
            'proveedor_id' => $this->proveedor_id,
            'cantidad_caja' => $this->cantidad_caja,
        ]);

        $this->resetUI();
        $this->emit('item-updated', 'PRODUCTO ACTUALIZADO');
        $this->alert('success', 'PRODUCTO ACTUALIZADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    public function Destroy(Producto $producto)
    {
        //$categoria = Categoria::find($id);
        $producto->delete();
        $this->resetUI();
        $this->alert('success', 'PRODUCTO ACTUALIZADO CON EXITO', [
            'position' => 'center',
        ]);

    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function resetUI()
    {
        $this->producto_id = '';
        $this->categoria_id = '';
        $this->proveedor_id = '';
        $this->descripcion = '';
        $this->codigo_barras = 0;
        $this->precio_compra = 0;
        $this->precio_venta = 0;
        $this->precio_mayoreo = 0;
        $this->precio_oferta = 0;
        $this->seleccionar_id = 0;
        $this->excel = '';
        $this->cantidad_caja = 0;
        $this->resetValidation();

    }

    public function cargaMasiva()
    {
        $rules = [
            'excel' => 'required|file|mimes:xls,xlsx',
        ];

        $messages = [
            'excel.required' => 'El archivo excel es requerido',
            'excel.mimes' => 'El archivo debe ser xls, xlsx',
        ];

        $this->validate($rules, $messages);

        try {

            Excel::import(new ProductosImport, $this->excel->getRealPath());
            $this->emit('masivaModal-hide', 'cierra modal');
            $this->alert('success', 'PRODUCTOS IMPORTADOS CON EXITO', [
                'position' => 'center',
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('import_errors', $failures);
        }
         

    }

    public function export()
    {
        return Excel::download(new ProductosExport, 'productos.xlsx');
    }

     public function closeModal()
     {
         $this->showModal = false;
         $this->resetUI();
     }
}
