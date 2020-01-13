<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificacionController extends Controller
{
    //
    public function index($tipo){
        switch ($tipo) {
            case 1:
                return ['alertas' => User::find(1)->unreadNotifications];
                break;
        }
    }

    public function leer(Request $request){
        DatabaseNotification::find($request->id)->markAsRead();
    }

}
