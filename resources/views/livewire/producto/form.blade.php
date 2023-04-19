@include('common.modalHead')
<div class="row">
    <div class="col-sm-12">
        <div class="mb-2">
            <span><strong>Ingrese el Nombre de la Categoria</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="nombre" class="form-control" placeholder="ej: Perfumeria">
        </div>
        @error('nombre')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalFooter')
