<div>
    <div class="page-header">
        <div class="page-title">
            <h3>Registrar Compras | Productos</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <input type="text" wire:model="search" placeholder="Buscar" class="form-control">
                                    <div class="search-table">
                                        @if (!empty($search))
                                            <table class="table table-bordered mb-4">
                                                <tbody>
                                                    @foreach ($productos as $producto)
                                                        <tr>
                                                            <td>
                                                                <span class="form-control"
                                                                    wire:click="agregarProducto({{ $producto->id }})">
                                                                    {{ $producto->descripcion }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                    <div class="mt-4">
                                        <table class="table table-bordered mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th class="text-center">Cantidad</th>
                                                    <th class="text-center">Precio</th>
                                                    <th class="text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($productosAComprar as $index => $producto)
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" aria-label="Disabled" disabled
                                                                wire:model="productosAComprar.{{ $index }}.producto_id">
                                                                @foreach ($productos as $producto)
                                                                    <option value="{{ $producto->id }}">
                                                                        {{ $producto->descripcion }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="number"
                                                                wire:model="productosAComprar.{{ $index }}.cantidad"
                                                                placeholder="Cantidad a comprar">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="number"
                                                                wire:model="productosAComprar.{{ $index }}.precio"
                                                                placeholder="{{ $productoPrecio }}">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger" type="button"
                                                                wire:click="eliminarProducto({{ $index }})">Eliminar</button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">AGREGA PRODUCTOS A LA
                                                            COMPRA</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="card">
                                        <div class="card-header" style="background: #4361ee">
                                            <div class="card-title text-center">
                                                <h3 style="color: #ebe3e3;">Total Compra : $
                                                    {{ $totalCompra }}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="mb-2">
                                                    <label>Proveedor</label>
                                                    <select class="form-select" wire:model="proveedorId">
                                                        <option value="">Selecciona un proveedor</option>
                                                        @foreach ($proveedores as $proveedor)
                                                            <option value="{{ $proveedor->id }}">
                                                                {{ $proveedor->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Documento</label>
                                                    <select class="form-select" wire:model="documento">
                                                        <option>Selecciona un Documento</option>
                                                        <option>Factura</option>
                                                        <option>Boleta</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Nro. Documento</label>
                                                    <input wire:model="numDocumento" type="text"
                                                        class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                    <label>Forma de Pago</label>
                                                    <select class="form-select" wire:model="tipoPago">
                                                        <option>Selecciona tipo de pago</option>
                                                        <option>Efectivo</option>
                                                        <option>Credito</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button class="btn btn-info" wire:click.prevent="store">Realizar la
                                                Compra</button>
                                            <button class="btn btn-danger float-end" wire:click.prevent="resetUI">Vaciar
                                                Listado</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-8">
                                    <form wire:submit.prevent="store">
                                        @foreach ($productosAComprar as $index => $producto)
                                            <select wire:model="productosAComprar.{{ $index }}.producto_id">
                                                <option value="">Selecciona un producto</option>
                                                @foreach ($productos as $producto)
                                                    <option value="{{ $producto->id }}">{{ $producto->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="number"
                                                wire:model="productosAComprar.{{ $index }}.cantidad"
                                                placeholder="Cantidad a comprar">
                                            <input type="number"
                                                wire:model="productosAComprar.{{ $index }}.precio"
                                                placeholder="Precio de compra">
                                            <button type="button"
                                                wire:click="eliminarProducto({{ $index }})">Eliminar</button>
                                        @endforeach
                                        <button type="button" wire:click="agregarProducto">Agregar
                                            producto</button>
                                        <button type="submit">Realizar compra</button>
                                    </form>
                                </div>

                                <div class="col-4">
                                    <form wire:submit.prevent="store">
                                        <div class="form-group">
                                            @foreach ($productosAComprar as $index => $producto)
                                                <select wire:model="proveedorId">
                                                    <option value="">Selecciona un proveedor</option>
                                                    @foreach ($proveedores as $proveedor)
                                                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" wire:model="documento" placeholder="Documento">
                                        </div>
                                        <div class="form-group">
                                            <input type="number" wire:model="numDocumento"
                                                placeholder="Numero Documento">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" wire:model="tipoPago" placeholder="Tipo de Pago">
                                        </div>
                                        @endforeach
                                </div>
                                </form>                                 --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


{{-- <script>
    ocument.addEventListener('DOMContentLoaded', function() {
        window.open("print://" + saleId , '_blank')
     })
</script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        livewire.on('scan-code', action => {
            $('#code').val('')
        })
    });

    function Confirm(id) {
        swal.fire({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL PRODUCTO?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            confirmButtonText: 'Aceptar'

        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('removeItem', id)
                swal.close()
            }
        })
    };
</script>
