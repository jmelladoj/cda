<?php

namespace App\Http\Controllers;

use App\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    //
    public function index($tipo){
        switch ($tipo) {
            case 1:
                return ['proveedores' => Proveedor::with('productos')->orderBy('nombre', 'asc')->get()];
                break;
            case 2:
                return ['proveedores' => Proveedor::with('productos')->withTrashed()->orderBy('nombre', 'asc')->get()];
                break;
        }
    }

    public function crear_actualizar(Request $request){
        Proveedor::updateOrCreate(
            ['id' => $request->id],
            [
                'nombre' => $request->nombre,
                'rut' => $request->rut,
                'giro' => $request->giro,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'comuna' => $request->comuna,
                'correo' => $request->correo,
                'referencia' => $request->referencia
            ]
        );
    }

    public function borrar(Request $request){
        Proveedor::find($request->id)->delete();
    }
}
