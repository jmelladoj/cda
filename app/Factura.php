<?php

namespace App;

use App\OrdenCompra;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    //
    protected $table = 'facturas';
    protected $guarded = ['id'];

    public function OrdenCompra(){
        return $this->belongsTo(OrdenCompra::class, 'orden_compras_id');
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function lugar(){
        return $this->belongsTo(Lugar::class, 'lugares_id');
    }
}
