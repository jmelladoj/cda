<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenCompra extends Model
{
    //
    use SoftDeletes;

    protected $table = 'orden_compras';
    protected $guarded = ['id'];

    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function getDescripcionOrdenAttribute(){
    	return explode('@', $this->descripcion);
  	}

    public function getCantidadOrdenAttribute(){
    	return explode('@', $this->cantidad);
  	}

    public function getValorUnitarioOrdenAttribute(){
    	return explode('@', $this->valor_unitario);
  	}

    public function getMedidaOrdenAttribute(){
        return explode('@', $this->unidad_medida);
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
