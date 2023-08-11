<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div>
            {{-- <a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#theModal">
                Agregar
            </a> --}}
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="input-group mb-3">
                        <h5 class="col-lg-9">Sucursal : {{ $sucursalNombre }} </h5>
                        <div class="col-lg-1">
                            <p>Selecciona una fecha</p>
                        </div>                        
                        <input type="date" class="form-control col-lg-2" wire:model="fechaSeleccionada">                             
                    </div>
                    <div class="table-responsive">
                        @include('common.searchbox')
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>Descripcion</th>
                                    <th class="text-center">Motivo</th>                                    
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Habia</th>
                                    <th class="text-center">Hay</th>
                                    <th class="text-center">Fecha</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historiales as $data)
                                    <tr>
                                        <td>{{ $data->productos->descripcion }}</td>
                                        <td class="text-center">{{ $data->motivo }}</td>
                                        <td class="text-center">{{ $data->tipo }}</td>
                                        <td class="text-center">{{ $data->cantidad }}</td>                                        
                                        <td class="text-center">{{ $data->stock_antiguo }}</td>
                                        <td class="text-center">{{ $data->stock_nuevo }}</td>
                                        <td class="text-center">{{ $data->created_at }}</td>                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $historiales->links() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('livewire.categoria.form') --}}
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

    });

    function Confirm(id) {
        
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
