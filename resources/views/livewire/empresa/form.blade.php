@include('common.modalHead')
<div class="row">
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Razón Social</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="razon_social" class="form-control" placeholder="">
        </div>
        @error('razon_social')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>RUT</strong></span>
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
            <span><strong>Email</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="email" class="form-control">
        </div>
        @error('email')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>I.V.A</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="iva" class="form-control">
        </div>
        @error('iva')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalFooter')
