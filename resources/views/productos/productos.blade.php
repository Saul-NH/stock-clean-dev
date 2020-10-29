@extends('layouts.app')

@section('content')
    @if(session('mensaje'))
        <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{session('mensaje')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-6"></div>
            <div class="col-6">
                <div class="form-group float-right">
                    <a class="btn btn-success " href="{{route('productos.create')}}">
                        Nuevo producto
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="form-group float-right">
            <input type="text" class="search form-control form-control-lg" placeholder="Buscar">
        </div>
        <span class="counter float-right h5"></span>
        <div class="table-responsive">
            <table class="table table-hover table-bordered results">
                <thead class="thead-dark position-sticky">
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Cantidad</th>
                        <th class="fit" scope="col">Precio de compra</th>
                        <th class="fit" scope="col">Precio de venta</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                <tr class="warning no-result">
                    <td colspan="5"><i class="fa fa-warning"></i> Sin resultados</td>
                </tr>
                </thead>
                    <tbody>
                     @foreach($productos as $producto)
                        <tr id="fila_{{$producto->id}}">
                            <td class="fit h5 ">{{$producto->name}}</td>
                            <td class="fit h5 ">{{ getFormatAmount($producto->cantidad_stock,$producto->tipo_medida) }} </td>
                            <td class="fit h5 ">{{ getFormatPrice($producto->precio_compra,$producto->tipo_medida) }} </td>
                            <td class="fit h5 ">{{ getFormatPrice($producto->precio_venta,$producto->tipo_medida) }} </td>
                            <td class="fit">
                                <div class="form-row">

                                    <div class="col pb-2">
                                        <a class="btn btn-primary form-control" href="{{route('productos.edit',$producto->id)}}">Modificar
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="col">
                                        <form id="form{{$producto->id}}" action="{{route('productos.destroy',$producto->id)}}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger eliminar form-control" data-id="{{$producto->id}}"  data-toggle="modal" data-target="#deleteProduct" onclick="event.preventDefault();">
                                                Dar de baja
                                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                            </button>

                                        </form>
                                    </div>

                                </div>
                            </td>
                        </tr>
                     @endforeach

                    </tbody>
            </table>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteProduct" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Borrar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Deseas borrar el producto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="botonEliminar" class="btn btn-danger">Borrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
