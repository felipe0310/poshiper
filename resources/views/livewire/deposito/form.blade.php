<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #0654a1">
                <h5 class="modal-title text-white">
                    <b>Agregar</b> | Productos
                </h5>
                <button class="btn btn-danger" wire:click="agregarTodosLosProductos">Agregar todos los productos</button>
                <h6 class="text-center text-warning" wire:loading>Por Favor Espere</h6>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Producto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productosDisponibles as $producto)
                            <tr>
                                <td>{{ $producto->descripcion }}</td>
                                <td>
                                    <button class="btn btn-primary"
                                        wire:click.prevent="Store({{$producto->id}})">Agregar al inventario</button>
                                </td>
                            </tr>
                            @empty
                                    <tr>
                                        <td colspan="6"> No se encontraron productos </td>
                                    </tr>
                            @endforelse                       
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="closeModal()" class="btn btn-button btn-danger"
                    data-bs-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('refresh', function () {
            location.reload();
        });
    });
</script>
