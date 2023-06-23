<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div class="mb-2">
            <a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#theModal">
                Agregar
            </a>
            <a href="{{ url('/cajas') }}" class="btn btn-danger">
                Regresar
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div>
                        <h5 class="col-sm-12">Caja Sucursal : {{ $sucursalNombre }} </h5>
                    </div>
                    <div class="table-responsive">
                        @include('common.searchbox')
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>Fecha Apertura</th>
                                    <th>Fecha Cierre</th>
                                    <th>Monto Apertura</th>
                                    <th>Ingresos</th>
                                    <th>Egreso</th>
                                    <th>Monto Cierre</th>
                                    <th>Usuario</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cajas as $caja)
                                    <tr>
                                        <td>{{ $caja->fecha_apertura }}</td>
                                        <td>{{ $caja->fecha_cierre }}</td>
                                        <td>{{ $caja->monto_apertura }}</td>
                                        <td>{{ $caja->monto_ingreso }}</td>
                                        <td>{{ $caja->monto_egreso }}</td>
                                        <td>{{ $caja->monto_cierre }}</td>
                                        <td>{{ $caja->usuarios->name }}</td>
                                        @if ($caja->estado == 1)
                                            <td>Caja Abierta</td>
                                        @else
                                            <td>Caja Cerrada</td>
                                        @endif

                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-success"
                                                wire:click="ingreso('{{ $caja->id }}')" title="Agregar">
                                                <i class="fas fa-plus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-info" {{-- wire:click="Restar('{{ $inventario->id }}')" --}}
                                                title="Ajustar">
                                                <i class="fas fa-minus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-warning" {{-- wire:click="Traslado('{{ $inventario->id }}')"  --}}
                                                title="Trasladar">
                                                <i class="fas fa-share" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-primary" {{-- wire:click="Edit('{{ $inventario->id }}')"  --}}
                                                title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger" {{-- onclick="Confirm('{{ $inventario->id }}','{{ $inventario->stock }}')" --}}
                                                title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"> No se encontraron registros </td>
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
        {{-- @include('livewire.caja.form-traslado') --}}
        @include('livewire.caja.form-ingreso')
        {{-- @include('livewire.caja.form-restar')
        @include('livewire.caja.form-edit') --}}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('item-added', msg => {
            $('#theModal').modal('hide');
        })

        window.livewire.on('item-updated', msg => {
            $('#theModal').modal('hide');
        })

        window.livewire.on('item-delete', msg => {})

        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide');
        })

        window.livewire.on('modal-show', msg => {
            $('#theModal').modal('show')
        })

        $('#theModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

        window.livewire.on('modal-show-traslado', msg => {
            $('#trasladoModal').modal('show')
        })

        window.livewire.on('item-traslado', msg => {
            $('#trasladoModal').modal('hide');
        })

        $('#trasladoModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

        window.livewire.on('modal-show-ingreso', msg => {
            $('#ingresoModal').modal('show')
        })

        window.livewire.on('modal-hide-ingreso', msg => {
            $('#ingresoModal').modal('hide');

        })

        $('#sumarModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

        window.livewire.on('modal-show-restar', msg => {
            $('#restarModal').modal('show')
        })

        window.livewire.on('item-restar', msg => {
            $('#restarModal').modal('hide');
        })

        $('#restarModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

        window.livewire.on('modal-show-editar', msg => {
            $('#editarModal').modal('show')
        })

        window.livewire.on('item-editar', msg => {
            $('#editarModal').modal('hide');
        })

        $('#editarModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })


    });

    document.addEventListener('livewire:load', function() {
        Livewire.on('refreshComponent', function() {
            Livewire.refresh();
        });
    });

    function Confirm(id, stock) {
        if (stock > 0) {
            Swal.fire({
                title: 'NO SE PUEDE ELIMINAR EL PRODUCTO PORQUE TIENE STOCK DISPONIBLE',
                icon: 'error',
            })
            return;
        }
        Swal.fire({
            title: 'ATENCIÓN',
            text: '¿CONFIRMAS ELIMINAR EL PRODUCTO?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('deleteRow', id)
                swal.close()
            }
        })
    }
</script>
