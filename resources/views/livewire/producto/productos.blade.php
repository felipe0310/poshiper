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
                                    <th class="text-center">Código de Barras</th>
                                    <th class="text-center">Precio Compra</th>
                                    <th class="text-center">Precio Venta</th>
                                    <th class="text-center">Precio Mayoreo</th>
                                    <th class="text-center">Precio Oferta</th>
                                    <th class="text-center">Categoria</th>
                                    <th class="text-center">Stock</th>

                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->codigo_barras }}</td>
                                        <td>${{number_format($producto->precio_compra,0,",",".")}}</td>
                                        <td>${{number_format( $producto->precio_venta,0,",",".") }}</td>
                                        <td>${{number_format( $producto->precio_mayoreo,0,",",".") }}</td>
                                        <td>${{number_format( $producto->precio_oferta,0,",",".") }}</td>
                                        <td>{{ $producto->categorias}}</td>
                                        <td>0</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Edit('{{ $producto->id }}')" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $producto->id }}')"
                                                title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $productos->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.producto.form')
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

        $('#theModal').on('hidden.bs.modal', function(e) {
            $('.er').css('display', 'none');
        })

    });

    function Confirm(id, productos) {
        if (productos > 0) {
            swal('NO SE PUEDE ELIMINAR El PRODUCTO PORQUE TIENE STOCK DISPONIBLE')
            return;
        }
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
