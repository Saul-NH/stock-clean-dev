<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function user(){

        return $this->belongsTo('App\User', 'user_id');
    }

    public function products(){
        return $this->belongsToMany('App\Producto');
    }

    protected $fillable = [
        'total_venta'
    ];
}
