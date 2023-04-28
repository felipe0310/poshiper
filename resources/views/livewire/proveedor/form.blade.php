@include('common.modalHead')
<div class="row">
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Nombre</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="nombre" class="form-control">
        </div>
        @error('nombre')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Dirección</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="direccion" class="form-control">
        </div>
        @error('direccion')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Teléfono</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="telefono" class="form-control">
        </div>
        @error('telefono')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Email</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="email" class="form-control">
        </div>
        @error('email')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalFooter')
