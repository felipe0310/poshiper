@extends('layouts.theme.app')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h3>nombreComponente | tituloPagina</h3>            
        </div>
        <div class="">
            <a href="javascript:void(0)" class="btn btn-primary">
                Agregar
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <div class="mt-2 mb-4">
                            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar">
                        </div>
                        <table class="table table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Perfumeria</td>

                                    <td class="text-center">

                                        <a href="javascript:void(0)" class="btn btn-warning">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                        </a>


                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        paginacion
                    </div>
                </div>
            </div>
        </div>
        incluye modal
    </div>
    <script>
        document.addEventListener('DOMContetLoaded', function() {});
    </script>
@endsection
