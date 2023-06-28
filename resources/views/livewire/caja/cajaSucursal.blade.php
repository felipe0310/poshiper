<div>
    <div class="page-header">
        <div class="page-title">
            <h3>{{ $nombreComponente }} | {{ $paginaTitulo }}</h3>
        </div>
        <div class="mb-2">
            @if(!$this->getCajaDelDia())
            <a href="javascript:void(0)" class="btn btn-info" wire:click.prevent="openAperturaModal">
                Abrir Caja
            </a>
            @endif
            <a href="{{ url('/cajas') }}" class="btn btn-danger">
                Regresar
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div>
                        <h5 class="col-sm-12">Caja Sucursal : {{ $sucursalNombre }} </h5>
                    </div>
                    <div class="table-responsive">
                        @include('common.searchbox')
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>Fecha Apertura</th>
                                    <th class="text-center">Fecha Cierre</th>
                                    <th class="text-center" >Monto Apertura</th>
                                    <th class="text-center" >Ingresos</th>
                                    <th class="text-center" >Egreso</th>
                                    <th class="text-center" >Monto Cierre</th>
                                    <th class="text-center" >Total Caja</th>
                                    <th class="text-center" >Usuario</th>
                                    <th class="text-center" >Estado</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cajas as $caja)
                                    <tr>
                                        <td>{{$caja->fecha_apertura}}</td>
                                        <td>{{$caja->fecha_cierre}}</td>
                                        <td>$ {{ number_format($caja->monto_apertura, 0, ',', '.') }}</td>
                                        <td>$ {{ number_format($caja->monto_ingreso, 0, ',', '.') }}</td>
                                        <td>$ {{ number_format($caja->monto_egreso, 0, ',', '.') }}</td>
                                        <td>$ {{ number_format($caja->monto_cierre, 0, ',', '.') }}</td>
                                        <td>$ {{ number_format(((($caja->monto_apertura)+($caja->monto_ingreso))-($caja->monto_egreso)-($caja->monto_cierre)), 0, ',', '.') }}</td>
                                        <td>{{ $caja->usuarios->name }}</td>
                                        @if ($caja->estado == 1)
                                            <td>Caja Abierta</td>
                                        @else
                                            <td>Caja Cerrada</td>
                                        @endif
                                        <td class="text-center">    
                                            @if(Carbon\Carbon::parse($caja->fecha_apertura)->format('Y-m-d') == $this->fechaActual->format('Y-m-d') && $caja->estado == 1)                                                                                   
                                            <a href="javascript:void(0)" class="btn btn-success"
                                                wire:click="ingreso('{{ $caja->id }}')" title="Ingreso">
                                                <i class="fas fa-plus" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-info" wire:click="egreso('{{ $caja->id }}')"
                                                title="Egreso">
                                                <i class="fas fa-minus" aria-hidden="true"></i>
                                            </a>
                                            @endif
                                            <a href="javascript:void(0)" class="btn btn-warning" {{-- wire:click="Traslado('{{ $inventario->id }}')"  --}}
                                                title="Trasladar">
                                                <i class="fas fa-share" aria-hidden="true"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-primary" {{-- wire:click="Edit('{{ $inventario->id }}')"  --}}
                                                title="Editar">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            @if($caja->estado == 1)
                                            <a href="javascript:void(0)" class="btn btn-danger" onclick="Confirm('{{ $caja->id }}')"
                                                title="Cerrar Caja">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </a>
                                            @endif
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10"> No se encontraron registros </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- {{ $cajas->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- @include('livewire.caja.form') --}}
        @include('livewire.caja.form-cierre')
        @include('livewire.caja.form-ingreso')
        @include('livewire.caja.form-egreso')
        @include('livewire.caja.form-apertura')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('modal-show-apertura', msg => {
            $('#aperturaModal').modal('show');
        })

        window.livewire.on('modal-hide-apertura', msg => {
            $('#aperturaModal').modal('hide');
        })

        window.livewire.on('modal-show-egreso', msg => {
            $('#egresoModal').modal('show')
        })

        window.livewire.on('modal-hide-egreso', msg => {
            $('#egresoModal').modal('hide');
        })        

        window.livewire.on('modal-show-ingreso', msg => {
            $('#ingresoModal').modal('show')
        })

        window.livewire.on('modal-hide-ingreso', msg => {
            $('#ingresoModal').modal('hide');

        })

        window.livewire.on('modal-show-cierre', msg => {
            $('#cierreModal').modal('show')
        })

        window.livewire.on('modal-hide-cierre', msg => {
            $('#cierreModal').modal('hide');
        })       

    });

    document.addEventListener('livewire:load', function() {
        Livewire.on('refreshComponent', function() {
            Livewire.refresh();
        });
    });

    function Confirm(id) {        
        swal.fire({
            title: 'ATENCIÓN',
            text: '¿CONFIRMAS CERRAR LA CAJA?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('cerrarModalOpen', id)
                swal.close()
            }
        })
    }
</script>
