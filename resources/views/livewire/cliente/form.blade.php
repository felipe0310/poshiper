@include('common.modalHead')
<div class="row">
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Rut</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="rut" class="form-control">
        </div>
        @error('rut')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
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
            <span><strong>Apellido</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="apellido" class="form-control">
        </div>
        @error('apellido')
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
            <span><strong>Limite de Crédito</strong></span>
        </div>
        <div class="input-group">
            <input type="number" wire:model.lazy="limite_credito" class="form-control">
        </div>
        @error('limite_credito')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Descuento</strong></span>
        </div>
        <div class="input-group">
            <input type="number" wire:model.lazy="descuento" class="form-control">
        </div>
        @error('descuento')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>     
</div>
@include('common.modalFooter')
