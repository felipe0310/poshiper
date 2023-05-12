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
                                    <th>Código de Barras</th>
                                    <th class="text-center">Descripcion</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($depositos as $deposito)
                                    <tr>
                                        <td>{{ $deposito->productos_codigo }}</td>
                                        <td>{{ $deposito->productos_descripcion }}</td>
                                        <td class="text-center">{{ $deposito->stock }}</td>

                                        <td class="text-center">
                                            <a href="javascript:void(0)" class="btn btn-success"
                                                wire:click="Sumar('{{ $deposito->id }}')" title="Agregar">
                                                <i class="fas fa-plus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-info"
                                                wire:click="Restar('{{ $deposito->id }}')" title="Ajustar">
                                                <i class="fas fa-minus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-warning"
                                                wire:click="Traslado('{{ $deposito->id }}')" title="Trasladar">
                                                <i class="fas fa-share" aria-hidden="true"></i>
                                            </a>
                                            {{-- <a href="javascript:void(0)" class="btn btn-primary"
                                                wire:click="Edit('{{ $deposito->id }}')" title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a> --}}
                                            <a href="javascript:void(0)" class="btn btn-danger"
                                                onclick="Confirm('{{ $deposito->id }}','{{ $deposito->stock }}')"
                                                title="Eliminar">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $depositos->links() }}
                    </div>
                </div>
            </div>
        </div>
        @include('livewire.deposito.form')
        @include('livewire.deposito.form-traslado')
        @include('livewire.deposito.form-sumar')
        @include('livewire.deposito.form-restar')
        @include('livewire.deposito.form-edit')
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
            swal.fire('NO SE PUEDE ELIMINAR EL PRODUCTO PORQUE TIENE STOCK DISPONIBLE')
            return;
        }
        swal.fire({
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
