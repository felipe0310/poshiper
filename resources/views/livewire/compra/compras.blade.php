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
                                    <input type="text" wire:model="busqueda" wire:keydown.enter="buscarProducto"
                                        placeholder="Buscar" class="form-control">
                                    <div class="search-table">
                                        @if (!empty($busqueda))
                                            <table class="table table-bordered mb-4">
                                                <tbody>
                                                    @foreach ($productos as $producto)
                                                        <tr>
                                                            <td>
                                                                <span class="form-control"
                                                                    wire:click="agregarAlCarrito({{ $producto['id'] }})">
                                                                    {{ $producto['descripcion'] }}
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
                                                    <th class="text-center">Precio Compra</th>
                                                    <th class="text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($carrito  as $indice => $item)
                                                    <tr>
                                                        <td style="width: 50%">
                                                            <label>{{ $item['nombre'] }}</label>
                                                        </td>
                                                        <td>
                                                            <input class="form-control text-center" type="number"
                                                                wire:model="cantidades.{{ $indice }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control text-center" type="number"
                                                                wire:model="precios.{{ $indice }}"
                                                                value="{{ $precios[$indice] }}">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger" type="button"
                                                                onClick="Confirm({{ $indice }})">Eliminar</button>
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
                                                    {{ number_format($total, 0, ',', '.') }}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-2">
                                                <div class="mb-2">
                                                    <label>Proveedor</label>
                                                    <select class="form-select" wire:model="proveedor_id">
                                                        <option value="">Selecciona un proveedor</option>
                                                        @foreach ($proveedores as $proveedor)
                                                            <option value="{{ $proveedor->id }}">
                                                                {{ $proveedor->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Tipo Documento</label>
                                                    <select class="form-select" wire:model="documento">
                                                        <option>Selecciona un Documento</option>
                                                        <option>Factura</option>
                                                        <option>Boleta</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Nro. Documento</label>
                                                    <input wire:model="num_documento" type="text"
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
                                            <button class="btn btn-info" wire:click.prevent="realizarCompra">Realizar la
                                                Compra</button>
                                            <button class="btn btn-danger float-end" wire:click.prevent="resetUI">Vaciar
                                                Listado</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

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
