<div wire:ignore.self class="modal fade" id="trasladoModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #0654a1">
                <h5 class="modal-title text-white">Trasladar stock</h5>
                <h6 class="text-center text-warning" wire:loading>Por Favor Espere</h6>
            </div>
            <div class="modal-body">
                <!-- Formulario de traslado de stock -->
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="mb-2 mt-2">
                            <span><strong>Almacén de origen</strong></span>
                        </div>
                        <div>
                            <h5 wire:model="almacenOrigen"><strong>Bodega</strong></h5>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12">
                        <div class="mb-2 mt-2">
                            <span><strong>Producto</strong></span>
                        </div>
                        <select class="custom-select col-12" wire:model="producto_id" aria-label="Disabled" disabled>
                            <option selected>Seleccione el Producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-lg-12">
                        <div class="mb-2 mt-2">
                            <span><strong>Almacén de destino</strong></span>
                        </div>
                        <select class="custom-select col-12" wire:model="almacenDestino">
                            <option value="">Seleccione un almacén</option>
                            @foreach ($almacenes as $almacen)
                                <option value="{{ $almacen->id }}">{{ $almacen->descripcion }}</option>
                            @endforeach
                        </select>
                        @error('almacenDestino')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-lg-12">
                        <div class="mb-2 mt-2">
                            <span><strong>Stock Actual</strong></span>
                        </div>
                        <div class="input-group">
                            <input type="number" wire:model.lazy="stock" class="form-control" readonly>                            
                        </div>                                              
                    </div>
                    <div class="col-sm-12 col-lg-12">
                        <div class="mb-2 mt-2">
                            <span><strong>Cantidad a Enviar</strong></span>
                        </div>
                        <div class="input-group">
                            <input type="text" wire:model.lazy="stockIn" class="form-control" onkeypress='return validaNumericos(event)'>
                        </div>
                        @error('stockIn')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click.prevent="trasladarStock()"
                        class="btn btn-info close-modal">TRASLADAR</button>
                    <button type="button" wire:click.prevent="closeModal" class="btn btn-button btn-danger"
                        data-bs-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>
</div>
