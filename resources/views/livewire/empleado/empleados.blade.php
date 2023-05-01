<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div>
            <a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#theModal">
                Agregar
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        @include('common.searchbox')
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>RUT</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Telefono</th>
                                    <th>Dirección</th>                                    
                                    <th>Email</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empleados as $empleado)
                                    <tr>
                                        <td>{{ $empleado->rut }}</td>
                                        <td>{{ $empleado->nombres }}</td>
                                        <td>{{ $empleado->apellidos }}</td>
                                        <td>{{ $empleado->telefono }}</td>
                                        <td>{{ $empleado->direccion }}</td>
                                        <td>{{ $empleado->email }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Edit('{{ $empleado->id }}')" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $empleado->id }}')"
                                                title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $empleados->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.empleado.form')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('item-added', msg => {
            $('#theModal').modal('hide');
            noty(msg)
        })

        window.livewire.on('item-updated', msg => {
            $('#theModal').modal('hide');
            noty(msg)
        })

        window.livewire.on('item-delete', msg => {
            noty(msg)
        })

        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide');
            noty(msg)
        })

        window.livewire.on('modal-show', msg => {
            $('#theModal').modal('show')
        })

        $('#theModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

    });

    function Confirm(id) {
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
            type: 'warning',
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
