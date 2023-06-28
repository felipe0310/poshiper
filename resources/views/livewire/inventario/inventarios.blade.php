<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
    </div>    
    <div class="row">
        @foreach ($almacenes as $almacen)
            <div class="col-lg-4 mt-2">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h5 class="card-title">{{ $almacen->descripcion }}</h5>
                        <p class="card-text">{{ $almacen->ubicacion }}</p>
                        <button wire:click="verSucursal({{$almacen->id}})" class="btn btn-info">Ir a Inventario</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
