@include('common.modalHead')
<div class="row">
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2">
            <span><strong>Rut</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="rut" class="form-control">
        </div>
        @error('rut')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2">
            <span><strong>Nombres</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="nombres" class="form-control">
        </div>
        @error('nombres')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>    
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Apellidos</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="apellidos" class="form-control">
        </div>
        @error('apellidos')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>    
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Telefono</strong></span>
        </div>
        <div class="input-group">
            <input type="number" wire:model.lazy="telefono" class="form-control">
        </div>
        @error('telefono')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
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
    <div class="col-sm-12 col-lg-6">
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
