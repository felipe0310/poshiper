@include('common.modalHead')
<div class="row">    
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2">
            <span><strong>Documento</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="documento" class="form-control">
        </div>
        @error('documento')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2">
            <span><strong>Serie</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="serie" class="form-control">
        </div>
        @error('serie')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>    
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Cantidad</strong></span>
        </div>
        <div class="input-group">
            <input type="number" wire:model.lazy="cantidad" class="form-control" onkeypress='return validaNumericos(event)'>
        </div>
        @error('cantidad')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>    
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Sucursal</strong></span>
        </div>
        <select class="custom-select col-12" wire:model="almacen_id">
            <option selected>Seleccione la sucursal</option>
            @foreach ($almacenes as $almacen)
                <option value="{{ $almacen->id }}">{{ $almacen->descripcion }}</option>
            @endforeach
        </select>
        @error('categoria_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalFooter')
