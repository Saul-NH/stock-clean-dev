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

    <div class="container" id="mensaje">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div id="mensaje_content">
                        
                    </div>
                    <button id="button_dissmiss" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
        </div>

    <div class="container-fluid">
        
        <div class="row">
            <div class="col">
                <div class="float-right">
                    <button type="button" class="btn btn-danger btn-lg mb-3" id="cancelar_venta" data-toggle="modal" data-target="#cancelar_venta_modal" onclick="event.preventDefault();" hidden>
                            Cancelar venta
                            <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-x-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                    </button>
                </div>
            </div>            
        </div>
        <div class="row">
            
            <div class="col">
                <div class="form-group float-right">
                    <button type="button" id="cantidad_productos" class="btn btn-secondary rounded-circle btn-lg px-3 py-2 mr-3">
                       <span class="badge  badge-secondary rounded-circle" id="cantidad_productos_badge">
                           <div id="ventas">
                               -
                           </div>
                       </span>
                    </button>
                    <a class="btn btn-success nuevo" id="nueva_venta" href="#">
                        Nueva venta
                        <svg width="1.8em" height="1.8em" viewBox="0 0 16 16" class="bi bi-plus-circle-fill ml-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                    </a>
                    
                    <button class="btn btn-warning nuevo" id="terminar_venta" hidden>
                        Terminar venta
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-check2-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                              <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                        </svg>
                    </button>                    
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
                        <th class="fit" scope="col">Precio de venta</th>
                        <th class=" fit text-center" scope="col">Cantidad a vender</th>
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
                            <td class="fit h5 ">{{ getFormatPrice($producto->precio_venta,$producto->tipo_medida) }} </td>
                            <td class="fit h5">
                                <div class="row justify-content-center">
                                    <div class="col-md-7">
                                        <input type="number" min="1" step="any" id="cantidad_{{$producto->id}}" class="form-control form-control-lg limpiar-input-cantidad" placeholder="{{getFormatSell($producto->tipo_medida)}}">
                                        <div class="mensaje-cantidad" id="mensaje_{{$producto->id}}" hidden>
                                            <p class="text-danger text-center">Ingresa la cantidad</p>
                                        </div>

                                        <div id="stock_{{$producto->id}}" hidden>
                                            <p class="text-danger text-center">La cantidad a vender<br>es mayor a la que hay en stock </p>
                                        </div>

                                    </div>
                                </div>

                            </td>
                            <td class="fit text-center">
                                    <a id="agregar{{$producto->id}}" href="#" class="btn btn-success agregar-producto rounded-circle px-2 pb-2" data-id="{{$producto->id}}" data-producto="{{ $producto }}" hidden>
                                       <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-plus-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                          <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                        </svg>
                                    </a>
                                    
                                    <a id="{{$producto->id}}" data-id="{{$producto->id}}" data-producto="{{ $producto }}" class="btn btn-danger rounded-circle px-2 pb-2 borrar-producto" href="#" hidden>
                                        <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-dash-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                          <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                                        </svg>
                                    </a>
                            </td>
                        </tr>
                     @endforeach

                    </tbody>
            </table>
        </div>

    </div>

    <!-- Modal detalle de venta -->
        <div class="modal" id="detalle_venta" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Detalle de la venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="fit" scope="col">Producto</th>
                                        <th class="fit text-center" scope="col">Cantidad</th>
                                        <th class="fit text-center" scope="col">Sub-total</th>
                                    </tr>
                                </thead>
                                <tbody id="modal_body_detalle">
                                        
                                </tbody>
                            </table>
                    </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>

        {{-- Modal para cancelar venta --}}
        
        <div class="modal fade" id="cancelar_venta_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cancelar venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body h5">
                <p>¿Deseas cancelar la venta?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">No</button>
                <button id="boton_cancelar" type="button" class="btn btn-danger btn-lg">Cancelar la venta</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal para finalizar la venta -->
        <div class="modal fade" id="finalizar_venta_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">¿Finalizar la venta?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="fit" scope="col">Producto</th>
                                            <th class="fit text-center" scope="col">Cantidad</th>
                                            <th class="fit text-center" scope="col">Sub-total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal_body_finalizar">
                                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger size16" data-dismiss="modal">No</button>
                        <button type="button" id="terminar_venta_button" class="btn btn-success size16">Finalizar venta
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-check2-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                  <path fill-rule="evenodd" d="M8 2.5A5.5 5.5 0 1 0 13.5 8a.5.5 0 0 1 1 0 6.5 6.5 0 1 1-3.25-5.63.5.5 0 1 1-.5.865A5.472 5.472 0 0 0 8 2.5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

@endsection
