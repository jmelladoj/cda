<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    //
    public function logeado(){
        return ['usuario' => User::where('id', Auth::id())->with('perfil')->first()];
    }
}
