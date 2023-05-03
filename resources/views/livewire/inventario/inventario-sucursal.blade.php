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
                        @error('sinStock')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                        @error('producto_id')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                        @error('almacenOrigen')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror                        
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
                                                wire:click="Edit('{{ $inventario->id }}')" title="Agregar">
                                                <i class="fas fa-plus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-info"
                                                wire:click="Edit('{{ $inventario->id }}')" title="Ajustar">
                                                <i class="fas fa-minus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Traslado('{{ $inventario->id }}')" title="Trasladar">
                                                <i class="fas fa-share" aria-hidden="true"></i>
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

        window.livewire.on('modal-show-traslado', msg => {
            $('#trasladoModal').modal('show')
        })

        window.livewire.on('item-traslado', msg => {
            $('#trasladoModal').modal('hide');
            noty(msg)
        })

        $('#trasladoModal').on('hidden.modal', function(e) {
            $('.er').css('display', 'none');
        })



    });

    function Confirm(id, stock) {
        if (stock > 0) {
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
