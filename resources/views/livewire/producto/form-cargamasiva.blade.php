<div wire:ignore.self class="modal fade" id="masivaModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #0654a1">
                <h5 class="modal-title text-white">Carga Masiva | Producto</h5>
                <h6 class="text-center text-warning" wire:loading>Por Favor Espere</h6>
            </div>
            <div class="modal-body">
                <div>
                    <p><strong>¡ATENCION!</strong> Antes de hacer la importación de los productos, tiene que crear las
                        CATEGORÍAS y los PROVEEDORES en sus respectivos módulos.</p>
                </div>
                <div class="mt-4">
                    <form wire:submit.prevent="cargaMasiva" method="POST" enctype="multipart/form-data">
                        <div>
                            @csrf
                            <input type="file" wire:model="excel" id="file" style="display: none">
                            <label for="file" class="btn btn-warning col-12">Seleccionar Archivo "xls, xlsx"</label>
                        </div>
                        @error('excel')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                        <div class="modal-footer">
                            <button class="btn btn-info" type="submit">IMPORTAR</button>
                            <button type="button" wire:click.prevent="closeModal" class="btn btn-button btn-danger"
                                data-bs-dismiss="modal">CERRAR</button>
                        </div>
                    </form>
                </div>
                @if (session()->has('import_errors'))
                    <div class="alert alert-danger">
                        <strong>Se encontraron errores durante la importación:</strong>
                        <ul>
                            @foreach (session('import_errors') as $failure)
                                <li>{{ $failure->errors()[0] }} Verifique su archivo en la linea Nro -
                                    {{ $failure->row() }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
