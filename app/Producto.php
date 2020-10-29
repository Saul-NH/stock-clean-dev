<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{

	public $timestamps = false;
	
    public function ventas(){
        $this->belongsToMany('App\Venta');
    }

    protected $fillable = [
        'name','cantidad_stock','tipo_medida','precio_venta','precio_compra'
    ];
}
