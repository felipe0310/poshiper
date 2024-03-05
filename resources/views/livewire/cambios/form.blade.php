@include('common.modalHead')
<div class="row">
    <div class="col-sm-12 col-xl-6 mb-2">

        <div class="mb-2">
            <span><strong>Nro. Boleta</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="nroBoleta" class="form-control">
        </div>
        @error('nroBoleta')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-xl-6 mb-2">
        <div class="mb-2">
            <span><strong>Producto Ingresa</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="productoIngresa" class="form-control">
        </div>
        @error('productoIngresa')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-xl-6">
        <div class="mb-2">
            <span><strong>Cantidad Ingresa</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="cantidadIngresa" class="form-control" placeholder="ej: Perfumeria">
        </div>
        @error('cantidadIngresa')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-xl-6 mb-2">
        <div class="mb-2">
            <span><strong>Producto Sale</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="productoSale" class="form-control">
        </div>
        @error('productoSale')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-xl-6">
        <div class="mb-2">
            <span><strong>Cantidad Sale</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="cantidadSale" class="form-control" placeholder="ej: Perfumeria">
        </div>
        @error('cantidadSale')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalFooter')
