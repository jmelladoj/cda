<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaProducto extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['valorizacion_aproximada'];

    public function productos(){
        return $this->hasMany(ProductoInventario::class, 'categoria_producto_id');
    }

    public function getValorizacionAproximadaAttribute(){
        $total = 0;

        foreach($this->productos AS $item){
            $total += $item->stock * $item->valor_actual;
        }

        return $total;
    }

}
