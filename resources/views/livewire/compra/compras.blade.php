<div>
    <style></style>

    <div class="page-header">
        <div class="page-title">
            <h3>Compras | Productos</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <form class="search">
                            <div class="mt-2 mb-4">
                                <input type="text" wire:keydown.enter.prevent="$emit('scan-code', $('#code').val())"
                                    id="code" placeholder="Buscar" class="form-control search-form-control">
                            </div>
                        </form>
                        <div class="card-body">
                            @if ($total > 0)
                                <div class="table-responsive col-lg-12">
                                    <table class="table table-bordered table-striped mt-1">
                                        <thead>
                                            <tr>
                                                <th>DESCRIPCION</th>
                                                <th class="text-center">PRECIO</th>
                                                <th class="text-center">CANTIDAD</th>
                                                <th class="text-center">IMPORTE</th>
                                                <th class="text-center">ACCION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart as $item)
                                                <tr>
                                                    <td>{{ $item->descripcion }}</td>
                                                    <td>${{ number_format($item->precio_venta, 0, ',', '.') }}</td>
                                                    <td>
                                                        <input type="number" id="r{{ $item->id }}"
                                                            wire:change="updateQty({{ $item->id }}, $('#r' + {{ $item->id }}).val() )"
                                                            style="font-size: 1rem!important" class="form-control"
                                                            value="{{ $item->quantity }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <h6>
                                                            ${{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                        </h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <button onclick="Confirm('{{ $item->id }}', 'removeItem')"
                                                            class="btn btn-info">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <button wire:click.prevent="decreaseQty({{ $item->id }})"
                                                            class="btn btn-info">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <button wire:click.prevent="increaseQty({{ $item->id }})"
                                                            class="btn btn-info">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h5 class="text-center text-muted">
                                    AGREGA PRODUCTOS A LA VENTA
                                </h5>
                            @endif
                            <div wire:loading.inline wire:target="saveSale">
                                <h4 class="text-danger text-center">
                                    GUARDANDO LA VENTA.....
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <script>
    ocument.addEventListener('DOMContentLoaded', function() {
        window.open("print://" + saleId , '_blank')
     })
</script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        livewire.on('scan-code', action => {
            $('#code').val('')
        })
    })
</script>
