<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div>
            <a href="{{ url('/compras') }}" class="btn btn-info">
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
                                    <th>Num. Documento</th>
                                    <th>Documento</th>                                    
                                    <th>Proveedor</th>
                                    <th>Subtotal</th>
                                    <th>Iva</th>
                                    <th>Total</th>
                                    <th>Tipo Pago</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($compras as $compra)
                                    <tr>
                                        <td>{{ $compra->num_documento }}</td>
                                        <td>{{ $compra->documento }}</td>
                                        <td>{{ $compra->proveedores }}</td>
                                        <td>$ {{ number_format($compra->subtotal, 2, ',', '.') }}</td>
                                        <td>$ {{ number_format($compra->iva, 2, ',', '.') }}</td>
                                        <td>$ {{ number_format($compra->total_compra, 0, ',', '.') }}</td>
                                        <td>{{ $compra->tipo_pago }}</td>
                                        <td>{{ $compra->created_at->format('d-m-Y') }}</td>

                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Edit({{$compra->id}})" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $compra->id }}')"
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
                        {{ $compras->links() }}
                    </div>
                </div>
            </div>
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

        $('#theModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

        window.livewire.on('modal-show-editar', msg => {
            $('#editarModal').modal('show')
        })

        window.livewire.on('item-editar', msg => {
            $('#editarModal').modal('hide');            
        })

        



    });

    function Confirm(id, productos) {
        if (productos > 0) {
            swal.fire({
                title: 'NO SE PUEDE ELIMINAR LA CATEGORIA PORQUE TIENE PRODUCTOS RELACIONADOS',
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
</div>



