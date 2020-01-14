<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoInventario extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['valorizacion', 'nombre_categoria', 'nombre_proveedor'];

    public function getValorizacionAttribute(){
        return $this->stock * $this->valor_actual;
    }

    public function getNombreProveedorAttribute(){
        return $this->proveedor_id != null ? $this->proveedor->nombre : 'Sin proveedor';
    }

    public function getNombreCategoriaAttribute(){
        return $this->categoria_productos_id != null ? $this->categoria->nombre : 'Sin unidad';
    }

    public function categoria(){
        return $this->belongsTo(CategoriaProducto::class, 'categoria_productos_id');
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}
