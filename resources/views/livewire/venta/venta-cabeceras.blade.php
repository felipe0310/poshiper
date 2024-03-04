<div>
    <div class="page-header">
        <div class="page-title">
            <h3>Ventas</h3>
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
                                                    <th class="text-center">Precio Venta</th>
                                                    <th class="text-center">Cantidad</th>
                                                    <th class="text-center">Stock</th>
                                                    <th class="text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($carrito as $indice => $item)
                                                    <tr>
                                                        <td class="fs-6" style="width: 50%">
                                                            <p>{{ $item['nombre'] }}</p>
                                                        </td>
                                                        <td class="fs-6 text-center">
                                                            <p wire:model.lazy="carrito.{{ $indice }}.precios">$
                                                                {{ $item['precios'] }}</p>

                                                            {{-- <input class="form-control text-center" type="number"
                                                                aria-label="Disabled" disabled
                                                                wire:model.lazy="precios.{{ $indice }}"
                                                                value="{{ $precios[$indice] }}"> --}}
                                                        </td>
                                                        <td style="width: 12%">
                                                            <input class="form-control text-center" type="number"
                                                                wire:model.lazy="carrito.{{ $indice }}.cantidades">
                                                        </td>
                                                        <td class="fs-6 text-center">
                                                            <p>{{ $this->obtenerStockDisponible($item['producto_id']) }}
                                                            </p>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-danger" type="button"
                                                                onClick="Confirm({{ $indice }})">Eliminar</button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">AGREGA PRODUCTOS A LA
                                                            VENTA</td>
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
                                                <h3 style="color: #ebe3e3;">Total Venta : $
                                                    {{ number_format($total, 0, ',', '.') }}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="input-group-sm mb-2">
                                                <label>Nro. de Documento</label>
                                                <span><strong>{{ $numeroDocumento }}</strong></span>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="mb-2" wire:ignore>
                                                    <label>Cliente</label>
                                                    @error('cliente_id')
                                                        <span class="text-danger er">{{ $message }}</span>
                                                    @enderror
                                                    <select class="form-select form-select-sm cliente_id"
                                                        wire:model="cliente_id">
                                                        <option value="">Seleccione un cliente</option>
                                                        @foreach ($clientes as $cliente)
                                                            <option value="{{ $cliente->id }}">
                                                                {{ $cliente->nombre . ' ' . $cliente->apellido }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Tipo Documento</label>
                                                    @error('documento')
                                                        <span class="text-danger er">{{ $message }}</span>
                                                    @enderror
                                                    <select class="form-select form-select-sm" wire:model="documento">
                                                        @foreach ($docAlmacenes as $docAlmacen)
                                                            <option value="{{ $docAlmacen->documento }}">
                                                                {{ $docAlmacen->documento }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Forma de Pago</label>
                                                    @error('tipoPago')
                                                        <span class="text-danger er">{{ $message }}</span>
                                                    @enderror
                                                    <select class="form-select form-select-sm" wire:model="tipoPago">
                                                        <option>Seleccione Forma de Pago</option>
                                                        <option>Efectivo</option>
                                                        <option>Tarjeta</option>
                                                        <option>Transferencia</option>
                                                        <option value="Credito">Crédito</option>
                                                        <option>Mixto</option>
                                                    </select>
                                                </div>
                                                @if ($tipoPago === 'Efectivo')
                                                    <div class="mb-2">
                                                        <label>Efectivo Recibido</label>
                                                        <input class="form-control form-control-sm" type="number"
                                                            wire:model="pagoEfectivo" placeholder="0"
                                                            onkeypress='return validaNumericos(event)'>
                                                    </div>
                                                    <div class="text-center mt-2">
                                                        <h3>Vuelto: $
                                                            @if($pagoEfectivo < $total)
                                                            0
                                                            @else
                                                            {{ number_format($vuelto, 0, ',', '.') }}
                                                            @endif
                                                        </h3>
                                                    </div>
                                                @endif
                                                @if ($tipoPago === 'Mixto')
                                                    <div class="mb-2">
                                                        <div class="mb-2">
                                                            <label>Efectivo Recibido</label>
                                                            <input class="form-control form-control-sm" type="number"
                                                                wire:model="pagoEfectivo2" placeholder= 0 value="0"
                                                                onkeypress='return validaNumericos(event)'>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label>Tarjeta</label>
                                                            <input class="form-control form-control-sm" type="number"
                                                                wire:model="pagoTarjeta2" placeholder=0 value="0"
                                                                onkeypress='return validaNumericos(event)'>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label>Transferencia</label>
                                                            <input class="form-control form-control-sm" type="number"
                                                                wire:model="pagoTransferencia2" placeholder=0 value="0"
                                                                onkeypress='return validaNumericos(event)'>
                                                        </div>
                                                        <div class="text-center mt-2">
                                                        <h3>Por Cobrar : $
                                                            {{ number_format($vuelto, 0, ',', '.') }}
                                                        </h3>
                                                    </div>
                                                    </div>
                                                @endif

                                            </div>
                                            <button class="btn btn-info reset2"
                                                wire:click.prevent="realizarVenta">Cobrar</button>

                                            <button class="btn btn-danger float-end" id="btnReset"
                                                wire:click.prevent="resetUI">Vaciar
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

    <script>
        document.addEventListener('livewire:load', function() {
            $('.cliente_id').select2({
                minimumInputLength: 2,
                width: '100%',
            });

            $.fn.select2.defaults.set('language', 'es');

            $('#btnReset').click(function() {
                $(".cliente_id").val('4').trigger("change");
            });

            $('.reset2').click(function() {
                $(".cliente_id").val('4').trigger("change");
            });

            $('.cliente_id').on('change', function() {
                @this.set('cliente_id', this.value);
            });

        });
    </script>
</div>
