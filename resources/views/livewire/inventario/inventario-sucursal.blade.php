<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div class="mb-2">
            <a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#theModal">
                Agregar
            </a>
            <a href="{{ url('/inventarios') }}" class="btn btn-danger">
                Regresar
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div>
                        <h5 class="col-sm-12">Sucursal : {{ $sucursalNombre }} </h5>
                    </div>
                    <div class="table-responsive">
                        @include('common.searchbox')
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>Código de Barras</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Stock</th>
                                    <th>Stock Minimo</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventarios as $inventario)
                                    <tr>
                                        <td>{{ $inventario->productos->codigo_barras }}</td>
                                        <td>{{ $inventario->productos->descripcion }}</td>
                                        <td>{{ $inventario->productos->categorias->nombre }}</td>
                                        <td>{{ $inventario->stock }}</td>
                                        <td>{{ $inventario->stock_minimo }}</td>

                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-success"
                                                wire:click="Sumar('{{ $inventario->id }}')" title="Agregar">
                                                <i class="fas fa-plus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-info"
                                                wire:click="Restar('{{ $inventario->id }}')" title="Ajustar">
                                                <i class="fas fa-minus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Traslado('{{ $inventario->id }}')" title="Trasladar">
                                                <i class="fas fa-share" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-primary"
                                                wire:click="Edit('{{ $inventario->id }}')" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $inventario->id }}','{{ $inventario->stock }}')"
                                                title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"> No se encontraron productos </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $inventarios->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.inventario.form')
        @include('livewire.inventario.form-traslado')
        @include('livewire.inventario.form-sumar')
        @include('livewire.inventario.form-restar')
        @include('livewire.inventario.form-edit')
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

        window.livewire.on('modal-show-sumar', msg => {
            $('#sumarModal').modal('show')
        })

        window.livewire.on('item-sumar', msg => {
            $('#sumarModal').modal('hide');

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

    document.addEventListener('livewire:load', function () {
            Livewire.on('refreshComponent', function () {
                Livewire.refresh();
            });
        });

    function Confirm(id, stock) {
        if (stock > 0) {
            Swal.fire({
                title:'NO SE PUEDE ELIMINAR EL PRODUCTO PORQUE TIENE STOCK DISPONIBLE',
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
