<?php

namespace App\Http\Controllers;

use App\DetalleStock;
use App\ProductoInventario;
use App\SalidaProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function index_detalle($accion ,$id){
        return ['detalle' => DetalleStock::where('accion', $accion)->where('producto_id', $id)->orderBy('created_at', 'desc')->take(15)->get()];
    }

    public function index_salida_detalle($id){
        return ['detalle' => SalidaProducto::where('producto_id', $id)->orderBy('created_at', 'desc')->take(15)->get()];
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

    public function agregar_stock(Request $request){
        DB::transaction(function () use ($request) {
            DetalleStock::create([
                'detalle' => 'Se sumo una cantidad de ' . $request->cantidad_ingreso . ' al stock del producto por ' . Auth::user()->nombre,
                'cantidad' => $request->cantidad_ingreso,
                'accion' => 1,
                'valor_ingreso' => $request->valor_ingreso,
                'producto_id' => $request->producto_id,
                'user_id' => Auth::id()
            ]);
        });

        $producto = ProductoInventario::find($request->producto_id);
        $producto->valor_ultimo = $producto->valor_actual;
        $producto->valor_actual = $request->valor_ingreso;
        $producto->stock +=  $request->cantidad_ingreso;
        $producto->save();
    }

    public function retirar_stock(Request $request){
        DB::transaction(function () use ($request) {
            $producto = ProductoInventario::find($request->producto_id);

            foreach($request->detalle_retiro as $retiro){
                SalidaProducto::create(
                    [
                        'lugar_nombre' => $retiro['nombre'],
                        'valor_retiro' => $request['valor_retiro'],
                        'cantidad_retiro' => $retiro['cantidad_retiro'],
                        'costo_retiro' => $retiro['costo_salida'],
                        'producto_id' => $request['producto_id'],
                        'user_id' => Auth::id(),
                        'lugar_id' => $retiro['lugar_id']
                    ]
                );

                $producto->stock -=  $retiro['cantidad_retiro'];
            }

            $producto->save();
        });
    }

}
