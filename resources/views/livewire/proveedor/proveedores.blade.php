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
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proveedores as $proveedor)
                                    <tr>
                                        <td>{{ $proveedor->nombre }}</td>
                                        <td>{{ $proveedor->direccion }}</td>
                                        <td>{{ $proveedor->telefono }}</td>
                                        <td>{{ $proveedor->email }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Edit('{{ $proveedor->id }}')" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $proveedor->id }}'"
                                                title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $proveedores->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.proveedor.form')
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

        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        })

        $('#theModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

    });

    function Confirm(id) {
        //if (productos > 0) {
          //  swal('NO SE PUEDE ELIMINAR LA CATEGORIA PORQUE TIENE PRODUCTOS RELACIONADOS')
          //  return;
        //}
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
