<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    //
    use SoftDeletes;

    protected $table = 'proveedores';
    protected $guarded = ['id'];
    protected $appends = ['gasto_mensual'];

    public function getGastoMensualAttribute(){
        return $this->hasMany(OrdenCompra::class, 'proveedor_id')->whereYear('created_at', Carbon::now()->format('Y'))->whereMonth('created_at', Carbon::now()->format('m'))->sum('total');
    }

    public function productos(){
        return $this->hasMany(ProductoInventario::class, 'proveedor_id')->orderBy('nombre');
    }
}
