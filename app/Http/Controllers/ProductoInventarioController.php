<?php

namespace App\Http\Controllers;

use App\DetalleStock;
use App\Notifications\Alerta;
use App\ProductoInventario;
use App\SalidaProducto;
use App\User;
use Carbon\Carbon;
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
        return ['detalle' => DetalleStock::whereYear('created_at', Carbon::now()->format('Y'))->whereMonth('created_at', Carbon::now()->format('m'))->where('accion', $accion)->where('producto_id', $id)->orderBy('created_at', 'desc')->get()];
    }

    public function index_salida_detalle($id){
        return ['detalle' => SalidaProducto::whereYear('created_at', Carbon::now()->format('Y'))->whereMonth('created_at', Carbon::now()->format('m'))->where('producto_id', $id)->orderBy('created_at', 'desc')->take(15)->get()];
    }

    public function crear_actualizar(Request $request){
        ProductoInventario::updateOrCreate(
            ['id' => $request->id],
            [
                'nombre' => $request->nombre,
                'unidad' => $request->unidad,
                'categoria_productos_id' => $request->categoria_id,
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

            $producto = ProductoInventario::find($request->producto_id);
            $producto->valor_ultimo = $producto->valor_actual;
            $producto->valor_actual = $request->valor_ingreso;
            $producto->stock +=  $request->cantidad_ingreso;
            $producto->save();

            User::find(1)->notify(new Alerta('Ingreso de stock', 'Se han ingresado ' . $request->cantidad_ingreso . ' ' . $producto->unidad . ' a ' . $producto->nombre, Auth::user()->nombre, 'fa fa-plus'));
        });
    }

    public function retirar_stock(Request $request){
        DB::transaction(function () use ($request) {
            $producto = ProductoInventario::find($request->producto_id);
            $total_retiro = 0;

            foreach($request->detalle_retiro as $retiro){
                SalidaProducto::create(
                    [
                        'lugar_nombre' => $retiro['nombre'],
                        'valor_retiro' => $request['valor_retiro'],
                        'cantidad_retiro' => $retiro['cantidad_retiro'],
                        'costo_retiro' => $retiro['costo_salida'],
                        'unidad_retiro' => $producto->unidad,
                        'producto_id' => $request['producto_id'],
                        'user_id' => Auth::id(),
                        'lugar_id' => $retiro['lugar_id']
                    ]
                );

                $total_retiro += $retiro['cantidad_retiro'];
                $producto->stock -=  $retiro['cantidad_retiro'];
            }

            $producto->save();

            User::find(1)->notify(new Alerta('Retiro de stock', 'Se han retirado ' . $total_retiro . ' ' . $producto->unidad . ' a ' . $producto->nombre , Auth::user()->nombre, 'fa fa-minus-circle'));
        });
    }

}
