<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    //
    public function index($tipo){
        switch ($tipo) {
            case 1:
                return ['facturas' => Factura::with('OrdenCompra')->with('proveedor')->with('lugar')->orderBy('numero_factura', 'desc')->get()];
                break;
            case 2:
                return ['facturas' => Factura::with('OrdenCompra')->with('proveedor')->with('lugar')->withTrashed()->orderBy('numero_factura', 'desc')->get()];
                break;
        }
    }

    public function crear_actualizar(Request $request){
        Factura::updateOrCreate(
            ['id' => $request->id],
            [
                'fecha' => $request->fecha,
                'numero_factura' => $request->numero_factura,
                'observacion' => $request->observacion
            ]
        );
    }
}
