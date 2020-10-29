@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-info">

                <div class="card-header bg-info text-center h5 font-weight-bold">
                    {{ __('Nuevo producto') }}
                </div>

                <div class="card-body">
                    <form action="{{ route('productos.store')}}" method="post">
                        @csrf

                        <div class="form group">
                            <label class="h5" for="nombre-producto">Producto</label>
                            <input name="name" class="form-control form-control-lg mb-3 @error('name') is-invalid @enderror" id="nombre-producto" value="{{ old('name') }}" type="text" required autocomplete="name" placeholder="nombre del producto" >
                             
                             @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                             @enderror
                        </div>

                        <div class="form group">
                            <label class="h5" for="cantidad-producto">Cantidad</label>
                            <input name="cantidad_stock" class="form-control form-control-lg mb-3" id="cantidad-producto" type="number" step="any" min="1" value="{{ old('cantidad_stock') }}" required autocomplete="cantidad_stock" placeholder="0.0" >
                        </div>

                        <div class="form group">
                            <label class="h5" for="medida">Tipo de medida</label>
                            <select class="form-control form-control-lg mb-3" required name="tipo_medida" id="medida">
                                <option value="" selected>Seleccione una opción</option>
                                <option value="litro" @if( old('tipo_medida') == 'litro') selected @endif>Litros</option>
                                <option value="kg" @if( old('tipo_medida') == 'kg') selected @endif>kilogramos</option>
                                <option value="pieza" @if( old('tipo_medida') == 'pieza') selected @endif>Piezas</option>
                            </select>
                            {{ old('tipo_medida') }}
                        </div>

                        <div class="form group">
                            <label class="h5" for="precio-venta">Precio de venta</label>
                            <input name="precio_venta" class="form-control form-control-lg" id="precio-venta" type="number"  value="{{ old('precio_venta') }}" step="any" min="1" autocomplete="precio_venta" required placeholder="$">
                        </div>

                        <div class="form group">
                            <label class="h5" for="precio_compra">Precio de compra</label>
                            <input name="precio_compra" class="form-control form-control-lg" id="precio_compra" type="number"  value="{{ old('precio_compra') }}" step="any" min="1" autocomplete="precio_compra" required placeholder="$">
                        </div>

                        <button class="btn btn-primary btn-block mt-4 btn-lg" type="submit">Guardar</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
