<?php

namespace App\Http\Controllers;

use App\ProductoInventario;
use Illuminate\Http\Request;

class ProductoInventarioController extends Controller
{
    //
    public function index($tipo){
        switch ($tipo) {
            case 1:
                return ['productos' => ProductoInventario::orderBy('nombre', 'asc')->get()];
                break;
            case 2:
                return ['productos' => ProductoInventario::withTrashed()->orderBy('nombre', 'asc')->get()];
                break;
        }
    }

    public function crear_actualizar(Request $request){
        ProductoInventario::updateOrCreate(
            ['id' => $request->id],
            [
                'nombre' => $request->nombre,
                'stock' => $request->stock,
                'stock_critico' => $request->stock_critico,
                'valor_actual' => $request->valor_actual,
                'valor_ultimo' => $request->valor_ultimo
            ]
        );
    }

    public function borrar(Request $request){
        ProductoInventario::find($request->id)->delete();
    }
}
