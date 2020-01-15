<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public $successStatus = 200;

    public function getDataUser(Request $request){

        $user = Auth::guard('api')->user();
        $user['message'] = 'success';
        $user['status'] = 'Datos obtenido correctamente';

        return response()->json($user, $this->successStatus);
    }

}
