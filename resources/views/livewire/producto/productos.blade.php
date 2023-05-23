<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div>
            <a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#theModal">
                Agregar
            </a>
            <a href="javascript:void(0)" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#masivaModal">
                Carga Masiva
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
                            <div>
                                <a href="{{ url('productos/export') }}" class="btn btn-info mb-4">Exportar productos a
                                    Excel</a>
                            </div>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Proveedor</th>
                                    <th>Cant. Bulto</th>
                                    <th>P. Costo</th>
                                    <th>P. Venta</th>
                                    <th>P. Mayoreo</th>
                                    <th>P. Oferta</th>
                                    <th>Categoria</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->codigo_barras }}</td>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->proveedores }}</td>
                                        <td>{{ $producto->cantidad_caja }}</td>
                                        <td>$ {{ number_format($producto->precio_compra, 0, ',', '.') }}</td>
                                        <td>$ {{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                                        <td>$ {{ number_format($producto->precio_mayoreo, 0, ',', '.') }}</td>
                                        <td>$ {{ number_format($producto->precio_oferta, 0, ',', '.') }}</td>
                                        <td>{{ $producto->categorias }}</td>

                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Edit({{ $producto->id }})" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $producto->id }}')" title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">No se encontraron productos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $productos->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.producto.form')
        @include('livewire.producto.form-cargamasiva')
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

        window.livewire.on('masivaModal-modal', msg => {
            $('#masivaModal').modal('hide');

        })

        window.livewire.on('masivaModal-show', msg => {
            $('#masivaModal').modal('show')
        })

        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide');

        })

        window.livewire.on('modal-show', msg => {
            $('#theModal').modal('show')
        })

        $('#theModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })

    });

    function Confirm(id, productos) {
        if (productos > 0) {
            swal.fire({
                title: 'NO SE PUEDE ELIMINAR El PRODUCTO PORQUE TIENE STOCK DISPONIBLE',
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
