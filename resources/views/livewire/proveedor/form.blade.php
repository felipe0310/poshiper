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
</div>
@include('common.modalFooter')
