<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoInventario extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['valorizacion'];

    public function getValorizacionAttribute(){
        return $this->stock * $this->valor_actual;
    }
}
