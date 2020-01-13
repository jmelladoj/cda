<?php

namespace App\Http\Controllers;

use App\Mail\OrdenCompra as AppOrdenCompra;
use App\OrdenCompra;
use App\ProductoInventario;
use App\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;

class OrdenCompraController extends Controller
{
    //
    public function index($tipo){
        $fecha = Carbon::now();

        $gasto_diario = OrdenCompra::whereDate('created_at', $fecha->format('Y-m-d'))->get()->sum('total');
        $gasto_semanal = OrdenCompra::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->sum('total');
        $gasto_mensual = OrdenCompra::whereYear('created_at', $fecha->format('Y'))->whereMonth('created_at', $fecha->format('m'))->get()->sum('total');
        $gasto_anual = OrdenCompra::whereYear('created_at', $fecha->format('Y'))->get()->sum('total');

        switch ($tipo) {
            case 1:
                return [
                            'ordenes' => OrdenCompra::join('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id')
                                            ->select('orden_compras.*', 'proveedores.id as proveedor_id', 'proveedores.rut as proveedor_rut', 'proveedores.nombre as proveedor_nombre', 'proveedores.giro as proveedor_giro', 'proveedores.telefono as proveedor_telefono', 'proveedores.direccion as proveedor_direccion', 'proveedores.comuna as proveedor_comuna', 'proveedores.correo as proveedor_correo', 'proveedores.referencia as proveedor_referencia')
                                            ->orderBy('orden_compras.created_at', 'desc')->get(),
                            'gasto_diario' => $gasto_diario,
                            'gasto_semanal' => $gasto_semanal,
                            'gasto_mensual' => $gasto_mensual,
                            'gasto_anual' => $gasto_anual
                        ];
                break;
            case 2:
                return [
                            'ordenes' => OrdenCompra::withTrashed()->join('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id')
                                            ->select('orden_compras.*', 'proveedores.id as proveedor_id', 'proveedores.rut as proveedor_rut', 'proveedores.nombre as proveedor_nombre', 'proveedores.giro as proveedor_giro', 'proveedores.telefono as proveedor_telefono', 'proveedores.direccion as proveedor_direccion', 'proveedores.comuna as proveedor_comuna', 'proveedores.correo as proveedor_correo', 'proveedores.referencia as proveedor_referencia')
                                            ->orderBy('orden_compras.created_at', 'desc')->get(),
                            'gasto_diario' => $gasto_diario,
                            'gasto_semanal' => $gasto_semanal,
                            'gasto_mensual' => $gasto_mensual,
                            'gasto_anual' => $gasto_anual
                        ];
                break;
        }
    }

    public function crear(Request $request){
         DB::transaction(function () use ($request) {
            $productos = "";
            $cantidades = "";
            $valores_unitarios = "";
            $medidas = "";

            $proveedor = Proveedor::updateOrCreate(
                ['id' => $request->proveedor_id],
                [
                    'nombre' => $request->proveedor_nombre,
                    'rut' => $request->proveedor_rut,
                    'giro' => $request->proveedor_giro,
                    'telefono' => $request->proveedor_telefono,
                    'direccion' => $request->proveedor_direccion,
                    'comuna' => $request->proveedor_comuna,
                    'correo' => $request->proveedor_correo,
                    'referencia' => $request->proveedor_referencia
                ]
            );

            foreach($request->detalle_orden AS $key => $item){
                if($key > 0){
                    $productos .= "@";
                    $cantidades .= "@";
                    $valores_unitarios .= "@";
                    $medidas .= "@";
                }

                if($item['id'] > 0){
                    $producto = ProductoInventario::find($item['id']);
                    $producto->proveedor_id = $proveedor->id;
                    $producto->save();
                }


                $productos .= $item['producto_nombre'];
                $cantidades .= $item['cantidad'];
                $valores_unitarios .= $item['valor_unitario'];
                $medidas .= $item['medida'];
            }

            $orden_compra = OrdenCompra::create(
                [
                    'asunto' => $request->asunto,
                    'descripcion' => $productos,
                    'cantidad' => $cantidades,
                    'unidad_medida' => $medidas,
                    'valor_unitario' => $valores_unitarios,
                    'fecha' => $request->fecha,
                    'valor_neto' => $request->neto,
                    'iva' => $request->iva,
                    'total' => $request->total,
                    'observacion' => $request->observacion,
                    'proveedor_id' => $proveedor->id,
                    'user_id' => Auth::id()
                ]
            );


            if($request->accion == 1){
                Mail::to($proveedor->correo)->send(new AppOrdenCompra($proveedor, $orden_compra));
            }
        });
    }

    public function borrar(Request $request){
        OrdenCompra::find($request->id)->delete();
    }

    public function enviar(Request $request){
        $orden_compra = OrdenCompra::find($request->id);
        $proveedor = Proveedor::find($orden_compra->proveedor_id);

        Mail::to($proveedor->correo)->send(new AppOrdenCompra($proveedor, $orden_compra));
    }

    public function descargar($id){

        $orden_compra = OrdenCompra::find($id);
        $data = ['orden_compra' => $orden_compra];

        return PDF::loadView('pdf/orden_compra', $data)->setPaper('a4')->output();
    }
}
