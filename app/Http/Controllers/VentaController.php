<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\Producto;

class VentaController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();
        return view('ventas.ventas', [ 'productos' => $productos] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $productos_venta = $request->productos_venta;
        $total_venta = $request->total_venta;
        $productos_venta = getProductosVentaTotal($productos_venta);
        $venta = new Venta;
        //$user = auth()->user()->id;
        $venta->total_venta = $total_venta;
        $venta->user_id = auth()->user()->id;
        $venta->save();

       // $venta->products()->save($venta->id);
        
        
        foreach ($productos_venta as $producto) {
            
            $venta->products()->attach($producto->id, ['cantidad_medida'=>$producto->cantidad_vendida, 'precio_venta'=>$producto->precio_venta]);
        }

        return response ('¡Venta registrada con EXITO!'. "<br>".' Total = '.$total_venta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $venta1 = Venta::find($id);

       // $venta1->products()->attach(5);
        return response($venta1->products[0]->pivot->precio_venta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
