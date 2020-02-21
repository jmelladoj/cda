<?php

namespace App\Http\Controllers;

use App\Factura;
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
                            'ordenes' => OrdenCompra::with('lugar')->join('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id')
                                            ->select('orden_compras.*', 'proveedores.id as proveedor_id', 'proveedores.rut as proveedor_rut', 'proveedores.nombre as proveedor_nombre', 'proveedores.giro as proveedor_giro', 'proveedores.telefono as proveedor_telefono', 'proveedores.direccion as proveedor_direccion', 'proveedores.comuna as proveedor_comuna', 'proveedores.correo as proveedor_correo', 'proveedores.referencia as proveedor_referencia')
                                            ->orderBy('orden_compras.created_at', 'desc')->with('lugar')->get(),
                            'gasto_diario' => $gasto_diario,
                            'gasto_semanal' => $gasto_semanal,
                            'gasto_mensual' => $gasto_mensual,
                            'gasto_anual' => $gasto_anual
                        ];
                break;
            case 2:
                return [
                            'ordenes' => OrdenCompra::with('lugar')->withTrashed()->join('proveedores', 'orden_compras.proveedor_id', '=', 'proveedores.id')
                                            ->select('orden_compras.*', 'proveedores.id as proveedor_id', 'proveedores.rut as proveedor_rut', 'proveedores.nombre as proveedor_nombre', 'proveedores.giro as proveedor_giro', 'proveedores.telefono as proveedor_telefono', 'proveedores.direccion as proveedor_direccion', 'proveedores.comuna as proveedor_comuna', 'proveedores.correo as proveedor_correo', 'proveedores.referencia as proveedor_referencia')
                                            ->orderBy('orden_compras.created_at', 'desc')->get(),
                            'gasto_diario' => $gasto_diario,
                            'gasto_semanal' => $gasto_semanal,
                            'gasto_mensual' => $gasto_mensual,
                            'gasto_anual' => $gasto_anual
                        ];
                break;
            case 3:
                return ['num_orden' => OrdenCompra::max('id')];
                break;
        }
    }

    public function crear(Request $request){
         DB::transaction(function () use ($request) {

            $proveedor = Proveedor::updateOrCreate(
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

            foreach($request->ordenes_compra AS $key => $item){
                $productos = '';
                $cantidades = '';
                $valores_unitarios = '';
                $medidas = '';
                $estados = '';

                foreach ($item['detalle'] as $key => $detalle) {
                    if($key > 0){
                        $productos .= "@";
                        $cantidades .= "@";
                        $valores_unitarios .= "@";
                        $medidas .= "@";
                        $estados .= "@";
                    }

                    if($detalle['id'] > 0){
                        $producto = ProductoInventario::find($detalle['id']);
                        $producto->valor_ultimo = $producto->valor_actual;
                        $producto->valor_actual = $detalle['valor_unitario'];
                        $producto->proveedor_id = $proveedor->id;
                        $producto->save();
                    }


                    $productos .= $detalle['producto_nombre'];
                    $cantidades .= $detalle['cantidad'];
                    $valores_unitarios .= $detalle['valor_unitario'];
                    $medidas .= $detalle['medida'];
                    $estados .= 0;
                }

                $orden_compra = OrdenCompra::create(
                    [
                        'asunto' => $item['asunto'],
                        'descripcion' => $productos,
                        'cantidad' => $cantidades,
                        'unidad_medida' => $medidas,
                        'valor_unitario' => $valores_unitarios,
                        'estado_producto' => $estados,
                        'fecha' => $item['fecha'],
                        'valor_neto' => $item['neto'],
                        'iva' => $item['iva'],
                        'total' => $item['total'],
                        'observacion' => $item['observacion'],
                        'proveedor_id' => $proveedor->id,
                        'lugares_id' => $item['centro_costo_id'],
                        'user_id' => Auth::id()
                    ]
                );

                Factura::create([
                    'numero_factura' => 'POR DEFINIR',
                    'orden_compras_id' => $orden_compra->id,
                    'fecha' => 'POR DEFINIR',
                    'proveedor_id' => $proveedor->id,
                    'lugares_id' => $item['centro_costo_id']
                ]);

                if($request->accion == 1){
                    Mail::to($proveedor->correo)->send(new AppOrdenCompra($proveedor, $orden_compra));
                }
            }
        });
    }

    public function restar_sumar(Request $request){
        $orden_compra = OrdenCompra::find($request->id);

        $producto = ProductoInventario::where('nombre', $request->producto)->first();

        $productos = $orden_compra->descripcionOrden;
        $cantidades = $orden_compra->cantidadOrden;
        $estados = $orden_compra->estado;

        $estados_producto = '';

        for ($i=0; $i < count($productos); $i++) {
            if($i > 0){
                $estados_producto .=  '@';
            }

            if($productos[$i] == $request->producto && $cantidades[$i] == $request->cantidad){
                $estados[$i] = $request->accion == 1 ? 1 : 0;
            }

            $estados_producto .= $estados[$i];

        }

        if($request->accion == 1){
            $producto->stock +=  $request->cantidad;
        } else {
            $producto->stock -=  $request->cantidad;
        }

        $orden_compra->estado_producto = $estados_producto;
        $orden_compra->save();
        $producto->save();

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
