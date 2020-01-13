<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaProducto extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];

    public function productos(){
        return $this->hasMany(ProductoInventario::class, 'categoria_producto_id');
    }

}
