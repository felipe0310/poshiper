@include('common.modalHead')
<div class="row">
     <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Empresa</strong></span>
        </div>
        <select class="custom-select col-12" wire:model="empresa_id">
            <option selected>Seleccione la empresa</option>
            @foreach ($empresas as $empresa)
                <option value="{{ $empresa->id }}">{{ $empresa->rut}}</option>
            @endforeach
        </select>
        @error('empresa_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Descripción</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="descripcion" class="form-control">
        </div>
        @error('descripcion')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Ubicación</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="ubicacion" class="form-control">
        </div>
        @error('ubicacion')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>   
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Entrada</strong></span>
        </div>
        <div class="input-group">
            <input type="time" wire:model.lazy="entrada" class="form-control">
        </div>
        @error('entrada')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Salida</strong></span>
        </div>
        <div class="input-group">
            <input type="time" wire:model.lazy="salida" class="form-control">
        </div>
        @error('salida')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>    
</div>
@include('common.modalFooter')
