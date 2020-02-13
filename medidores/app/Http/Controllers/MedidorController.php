<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medidor;

class MedidorController extends Controller
{
    //
    public function index(){
        $datos = Medidor::orderBy('id', 'desc')->get();
        return view('welcome')->with(compact('datos'));
    }

    public function registrarDatos($dato_1, $dato_2, $dato_3, $dato_4, $dato_5, $dato_6){  
        
        $medidor = new Medidor();
        $medidor->voltaje = $dato_1;
        $medidor->corriente = $dato_2;
        $medidor->potencia = $dato_3;
        $medidor->factor_potencia = $dato_4;
        $medidor->frecuencia = $dato_5;
        $medidor->energia_entrega = $dato_6;
        $medidor->save();
    }
}
