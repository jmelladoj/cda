<?php

namespace App\Http\Controllers;

use App\Lugar;
use Illuminate\Http\Request;

class LugarController extends Controller
{
    //
    public function index($tipo){
        switch ($tipo) {
            case 1:
                return ['lugares' => Lugar::orderBy('nombre', 'asc')->get()];
                break;
            case 2:
                return ['lugares' => Lugar::withTrashed()->orderBy('nombre', 'asc')->get()];
                break;
        }
    }

    public function crear_atualizar(Request $request){
        Lugar::updateOrCreate(
            ['id' => $request->id],
            [
                'nombre' => $request->nombre
            ]
        );
    }

    public function borrar(Request $request){
        Lugar::find($request->id)->delete();
    }
}
