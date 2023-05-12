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
                                    <th>Razón Social</th>
                                    <th>Dirección</th>
                                    <th>Email</th>
                                    <th>I.V.A</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empresas as $empresa)
                                    <tr>
                                        <td>{{ $empresa->rut }}</td>
                                        <td>{{ $empresa->razon_social }}</td>
                                        <td>{{ $empresa->direccion }}</td>
                                        <td>{{ $empresa->email }}</td>
                                        <td>{{ $empresa->iva }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Edit('{{ $empresa->id }}')" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $empresa->id }}','{{$empresa->almacenes->count()}}')"
                                                title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $empresas->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.empresa.form')
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

        window.livewire.on('item-delete', msg => {
           
        })

        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide');
            
        })

        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        })

        $('#theModal').on('hidden.bs.modal', function(e) {
            $('.er').css('display', 'none');
        })

    });

    function Confirm(id,almacenes) {
        if (almacenes > 0 | id == 1) {
            swal.fire({
                title: 'NO SE PUEDE ELIMINAR LA EMPRESA PORQUE TIENE ALMACENES ASOCIADOS O ES LA EMPRESA PRINCIPAL',
                icon: 'error'
            })
            return;
        }
        swal.fire({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
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
