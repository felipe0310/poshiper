@include('common.modalHead')
<div class="row">        
    <div class="col-sm-12 col-lg-6">                
        <div class="mb-2 mt-2">
            <span><strong>Producto</strong></span>
        </div>
        <select class="custom-select col-12" wire:model="producto_id">
            <option selected>Seleccione el producto</option>
            @foreach ($productos as $producto)
                <option value="{{ $producto->id }}">{{ $producto->descripcion }}</option>
            @endforeach
        </select>
        @error('producto_id')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Stock</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="stock" class="form-control">
        </div>
        @error('stock')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalFooter')
