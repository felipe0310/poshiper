<div wire:ignore.self class="modal fade" id="cierreModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #0654a1">
                <h5 class="modal-title text-white">Cierre | Caja</h5>
                <h6 class="text-center text-warning" wire:loading>Por Favor Espere</h6>
            </div>
            <div class="modal-body">
                <!-- Formulario de traslado de stock -->
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="mb-2 mt-2">
                            <span><strong>Caja</strong></span>
                        </div>
                        <div>
                            <h5 wire:model="almacenOrigen"><strong>{{$sucursalNombre}}</strong></h5>                            
                        </div>
                    </div>                    
                    <div class="col-sm-12 col-lg-12">
                        <div class="mb-2 mt-2">
                            <span><strong>Monto</strong></span>
                        </div>
                        <div class="input-group">
                            <input type="number" wire:model.lazy="monto_cierre" class="form-control" onkeypress='return validaNumericos(event)' placeholder="0">                            
                        </div>
                        @error('monto_cierre')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror                                                
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cerrarCaja" class="btn btn-info close-modal">GUARDAR</button>  
                    <button type="button" wire:click.prevent="closeModal" class="btn btn-button btn-danger" >CERRAR</button>                                      
                </div>
            </div>
        </div>
    </div>
</div>

