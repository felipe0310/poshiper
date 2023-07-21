<div>
    <div>
        <div class="page-header">
            <div class="page-title">
                <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
            </div>
            <div class="mb-2">
                <a href="{{ url('/cajas') }}" class="btn btn-danger">
                    Regresar
                </a>
                <div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <div class="input-group mb-3">
                            <h5 class="col-lg-10">Caja Sucursal : {{ $sucursalNombre }} </h5>
                            <input type="date" class="form-control col-lg-2" wire:model="fechaSeleccionada">                            
                        </div>
                        <div class="table-responsive">
                            @include('common.searchbox')
                            <table class="table table-bordered mb-4">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th class="text-center">Motivo</th>
                                        <th class="text-center">Monto</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($movCajas as $movCaja)
                                        <tr>
                                            <td>{{ $movCaja->tipo }}</td>
                                            <td>{{ $movCaja->descripcion }}</td>
                                            <td>$ {{ number_format($movCaja->monto, 0, ',', '.') }}</td>
                                            <td>{{ $movCaja->fecha }}</td>
                                            <td>{{ $movCaja->usuarios->name }}</td>                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10"> No se encontraron registros </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $movCajas->links() }}
                        </div>
                    </div>
                </div>
            </div>
            {{-- @include('livewire.caja.form')
        @include('livewire.caja.form-cierre')
        @include('livewire.caja.form-ingreso')
        @include('livewire.caja.form-egreso')
        @include('livewire.caja.form-apertura') --}}
        </div>
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
