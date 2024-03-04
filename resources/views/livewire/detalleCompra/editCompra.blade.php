<div>
    <div class="page-header">
        <div class="page-title">
            <h3>Editar Compras | Productos</h3>
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
                                                                    wire:click="seleccionarProducto({{ $producto['id'] }})">
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
                                                @foreach ($detalle_compras as $index => $detalle_compra)
                                                    <tr>
                                                        <td style="width: 50%">
                                                            <input type="text"
                                                                wire:model="detalle_compras.{{ $index }}.producto.descripcion"
                                                                class="form-control">
                                                        </td>
                                                        <td>
                                                            <input class="form-control text-center" type="number"
                                                                wire:model="detalle_compras.{{ $index }}.cantidad">
                                                        </td>
                                                        <td>
                                                            <input class="form-control text-center" type="number"
                                                                wire:model.lazy="detalle_compras.{{ $index }}.producto.precio_compra">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger" type="button"
                                                                onClick="Confirm2({{ $index }})">Eliminar</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
                                                    @error('proveedor_id')
                                                        <span class="text-danger er">{{ $message }}</span>
                                                    @enderror
                                                    <select class="form-select" wire:model="compra.proveedor_id">
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
                                                    @error('documento')
                                                        <span class="text-danger er">{{ $message }}</span>
                                                    @enderror
                                                    <select class="form-select" wire:model="compra.documento">
                                                        <option>Selecciona un documento</option>
                                                        <option>Factura</option>
                                                        <option>Boleta</option>
                                                    </select>
                                                </div>

                                                <div class="input-group-sm mb-2">
                                                    <label>Nro. Documento</label>
                                                    @error('num_documento')
                                                        <span class="text-danger er">{{ $message }}</span>
                                                    @enderror
                                                    <input wire:model="compra.num_documento" type="text"
                                                        class="form-control sm">
                                                </div>

                                                <div class="mb-2">
                                                    <label>Forma de Pago</label>
                                                    @error('tipoPago')
                                                        <span class="text-danger er">{{ $message }}</span>
                                                    @enderror
                                                    <select class="form-select" wire:model="compra.tipo_pago">
                                                        <option>Selecciona forma de pago</option>
                                                        <option>Efectivo</option>
                                                        <option>Crédito</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <button class="btn btn-info" wire:click.prevent="update">Actualizar</button>
                                            <a class="btn btn-danger float-end"
                                                href="{{ url('comprasDetalle') }}">Volver</a>
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

    function Confirm2(id) {
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
</div>



