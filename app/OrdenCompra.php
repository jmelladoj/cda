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
    protected $appends = ['emisor'];

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

    public function getEstadoAttribute(){
        return explode('@', $this->estado_producto);
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lugar(){
        return $this->belongsTo(Lugar::class, 'lugares_id');
    }

    public function getEmisorAttribute(){
        return empty($this->usuario) ? 'Desconocido' : $this->usuario->nombre;
    }

}
