<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Lugar extends Model
{
    //
    use SoftDeletes;

    protected $table = 'lugares';
    protected $guarded = ['id'];
    protected $appends = ['gasto_diario', 'gasto_semanal', 'gasto_mensual', 'gasto_anual'];

    public function getGastoDiarioAttribute(){
        return $this->hasMany(SalidaProducto::class, 'lugar_id')->whereDate('created_at', Carbon::now()->format('Y-m-d'))->sum('costo_retiro');
    }

    public function getGastoSemanalAttribute(){
        return $this->hasMany(SalidaProducto::class, 'lugar_id')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('costo_retiro');
    }

    public function getGastoMensualAttribute(){
        return $this->hasMany(SalidaProducto::class, 'lugar_id')->whereYear('created_at', Carbon::now()->format('Y'))->whereMonth('created_at', Carbon::now()->format('m'))->sum('costo_retiro');
    }

    public function getGastoAnualAttribute(){
        return $this->hasMany(SalidaProducto::class, 'lugar_id')->whereYear('created_at', Carbon::now()->format('Y'))->sum('costo_retiro');
    }

    
}
