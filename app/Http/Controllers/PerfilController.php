<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perfil;

class PerfilController extends Controller
{
    //
    public function index($tipo){
        switch ($tipo) {
            case 1:
                return ['perfiles' => Perfil::orderBy('nombre', 'asc')->get()];
                break;
            case 2:
                return ['perfiles' => Perfil::withTrashed()->orderBy('nombre', 'asc')->get()];
                break;
        }
    }

    public function crear_actualizar(Request $request){
        Perfil::updateOrCreate(
            ['id' => $request->id],
            [
                'nombre' => $request->nombre,
                'menu_perfiles' => $request->menu_perfiles,
                'menu_usuarios' => $request->menu_usuarios,
                'menu_ordenes_compra' => $request->menu_ordenes_compra,
                'menu_lugares' => $request->menu_lugares,
                'menu_inventario' => $request->menu_inventario,
                'menu_proveedores' => $request->menu_proveedores,
                'menu_categorias_productos' => $request->menu_categorias_productos
            ]
        );
    }

    public function borrar(Request $request){
        Perfil::find($request->id)->delete();
    }
}
