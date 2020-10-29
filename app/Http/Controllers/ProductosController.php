<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();

        return view('productos.productos', [ 'productos' => $productos]);
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productos.crear');
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'name' => ['required','string'],
        'cantidad_stock' => ['required','numeric','gte:1'],
        'tipo_medida' => ['required', 'string'],
        'precio_venta' => ['required', 'numeric','gte:1'],
        'precio_compra' => ['required', 'numeric','gte:1']
        ]);
        
        $producto = Producto::create($request->all());
        $mensaje = '"'.$producto->name.'"'.' creado correctamente';
        return redirect('productos')->with('mensaje',$mensaje);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::find($id);
        return view('productos.edit')->with('producto',$producto);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
        'name' => ['required','string'],
        'cantidad_stock' => ['required','numeric','gte:1'],
        'tipo_medida' => ['required', 'string'],
        'precio_venta' => ['required', 'numeric','gte:1'],
        'precio_compra' => ['required', 'numeric','gte:1']
        ]);

        $producto = Producto::find($id);
        $producto->name = $request->name;
        $producto->cantidad_stock = $request->cantidad_stock;
        $producto->tipo_medida = $request->tipo_medida;
        $producto->precio_venta = $request->precio_venta;
        $producto->precio_compra = $request->precio_compra;
        $producto->save();
        $mensaje = '"'.$producto->name.'"'.' modificado correctamente';
        return redirect('productos')->with('mensaje',$mensaje);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        $mensaje = '"'.$producto->name.'"'.' borrado correctamente';
        $producto->delete();

        return back()->with('mensaje',$mensaje);
    }
}
