<?php

namespace App\Http\Controllers;

use App\CategoriaProducto;
use Illuminate\Http\Request;

class CategoriaProductoController extends Controller
{
    //
    public function index($tipo){
        switch ($tipo) {
            case 1:
                return ['categorias' => CategoriaProducto::with('productos')->orderBy('nombre', 'asc')->get()];
                break;
            case 2:
                return ['categorias' => CategoriaProducto::with('productos')->withTrashed()->orderBy('nombre', 'asc')->get()];
                break;
        }
    }

    public function crear_actualizar(Request $request){
        CategoriaProducto::updateOrCreate(
            ['id' => $request->id],
            [
                'nombre' => $request->nombre
            ]
        );
    }

    public function borrar(Request $request){
        CategoriaProducto::find($request->id)->delete();
    }
}
