@include('common.modalHead')
<div class="row">
    <div class="col-sm-12">
        <div class="mb-2">
            <span><strong>Nombre</strong></span>
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
            <span><strong>Codigo de Barras</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="codigo_barras" class="form-control">
        </div>
        @error('codigo_barras')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Precio de Compra</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="precio_compra" class="form-control">
        </div>
        @error('precio_compra')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Precio de Venta</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="precio_venta" class="form-control">
        </div>
        @error('precio_venta')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Precio Mayoreo</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="precio_mayoreo" class="form-control">
        </div>
        @error('precio_mayoreo')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-lg-6">
        <div class="mb-2 mt-2">
            <span><strong>Precio Oferta</strong></span>
        </div>
        <div class="input-group">
            <input type="text" wire:model.lazy="precio_oferta" class="form-control">
        </div>
        @error('precio_oferta')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="mb-2 mt-2">
            <span><strong>Categoria</strong></span>
        </div>
        <select class="custom-select col-12" wire:model="categoria_id">
            <option selected>Seleccione la categoria</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>
        @error('categoria_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

@include('common.modalFooter')


