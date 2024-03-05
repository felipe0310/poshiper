<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div class="mb-2">
            <a href="{{ url('/cambios') }}" class="btn btn-danger">
                Regresar
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="d-flex justify-content-between mb-2">
                        <h5>Sucursal : {{ $sucursalNombre }} </h5>
                        <div>
                            <a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#theModal">
                                Agregar
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        @include('common.searchbox')
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>Producto Ingresa</th>
                                    <th class="text-center">Cant. Ingresa</th>
                                    <th class="text-center">Producto Sale</th>
                                    <th class="text-center">Cant. Sale</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cambios as $cambio)
                                <tr>
                                    <td>{{ $cambio->producto_ingresa_id }}</td>
                                    <td>{{ $cambio->cantidad_ingresa }}</td>
                                    <td>{{ $cambio->producto_sale_id }}</td>
                                    <td>{{ $cambio->cantidad_sale }}</td>
                                    <td class="col-1">$
                                        {{ number_format($cambio->movimientos->monto, 0, ',', '.') }}
                                    <td>{{ $cambio->created_at }}</td>
                                    <td class="text-center">
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="btn btn-warning"
                                            wire:click="Edit('{{ $cambios->id }}')" title="Editar">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger"
                                            onclick="Confirm('{{ $cambios->id }}','{{ $cambios->productos->count() }}')"
                                            title="Eliminar">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center"> No se encontraron registros </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- {{ $cajas->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('livewire.caja.form') --}}
        @include('livewire.cambios.form')
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('modal-show-apertura', msg => {
            $('#aperturaModal').modal('show');
        })

        window.livewire.on('modal-hide-apertura', msg => {
            $('#aperturaModal').modal('hide');
        })

        window.livewire.on('modal-show-egreso', msg => {
            $('#egresoModal').modal('show')
        })

        window.livewire.on('modal-hide-egreso', msg => {
            $('#egresoModal').modal('hide');
        })

        window.livewire.on('modal-show-ingreso', msg => {
            $('#ingresoModal').modal('show')
        })

        window.livewire.on('modal-hide-ingreso', msg => {
            $('#ingresoModal').modal('hide');

        })

        window.livewire.on('modal-show-cierre', msg => {
            $('#cierreModal').modal('show')
        })

        window.livewire.on('modal-hide-cierre', msg => {
            $('#cierreModal').modal('hide');
        })

    });

    document.addEventListener('livewire:load', function() {
        Livewire.on('refreshComponent', function() {
            Livewire.refresh();
        });
    });

    function Confirm(id) {
        swal.fire({
            title: 'ATENCIÓN',
            text: '¿CONFIRMAS CERRAR LA CAJA?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('cerrarModalOpen', id)
                swal.close()
            }
        })
    }
    </script>
</div>