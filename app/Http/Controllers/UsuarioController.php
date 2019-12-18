<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    //
    public function logeado(){
        return ['usuario' => User::where('id', Auth::id())->with('perfil')->first()];
    }

    public function index($tipo){

        switch ($tipo) {
            case 1:
                return ['usuarios' => User::join('perfiles', 'users.perfil_id', '=', 'perfiles.id')
                                          ->select('users.*', 'perfiles.nombre as nombre_perfil')->orderBy('users.nombre')->get()];
                break;
            case 2:
                return ['usuarios' => User::withTrashed()->orderBy('nombre', 'asc')->get()];
                break;
        }
    }

    public function crear_actualizar(Request $request){
        $usuario = User::updateOrCreate(
            ['id' => $request->id],
            [
                'nombre' => $request->nombre,
                'usuario' => $request->usuario,
                'email' => $request->email,
                'ruta_inicio' => $request->ruta_inicio,
                'perfil_id' => $request->perfil_id,
            ]
        );

        if($request->password != ''){
            User::updateOrCreate(
                ['id' => $usuario->id],
                [
                    'password' => bcrypt($request->password)
                ]
            );
        }
    }

    public function borrar(Request $request){
        User::find($request->id)->delete();
    }

    public function validar_unico($texto){
        return json_encode(User::where('usuario', $texto)->get()->count() > 0 ? false : true);
    }

    public function actualizar_clave(Request $request){
        User::updateOrCreate(
            ['id' => $request->id],
            [
                'password' => bcrypt($request->password)
            ]
        );
    }
}
