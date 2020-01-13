<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Avatar;
use Illuminate\Support\Facades\Storage;
class AuthController extends Controller
{
    //

    /**
     * En caso de error ejeccutar lo siguiente
     * php artisan passport:client --personal
     */
    public $successStatus = 200;

    public function registerUser(Request $request){
        $validator = Validator::make($request->all(), [
            'id_type' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['required'],
            'device_token' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input["image"] = "avatar.png";
        $input["thumb_image"] = "avatar.png";
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);


        return response()->json(['status' => "success", 'message'=>'Te haz registrado con exito', 'token'=>$user->createToken($input['email'])->accessToken], $this->successStatus);


    }

    public function loginUser(){
        $validator = Validator::make(request()->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user = User::where('email',\request('email'))->first();
        if ($user){
            //verificar contraseña
            if (password_verify(\request('password'), $user->password)){
                return response()->json(['status' => "success", 'message'=>'Iniciaste sesión correctamente', 'token'=>$user->createToken($user->email)->accessToken], $this->successStatus);
            }else{
                return response()->json(['status' => "error", 'message'=>'Error al inciar sesion'], 401);
            }
        }else{
            return response()->json(['status' => "error", 'message'=>'Es posible que aun no estes registrado'], 401);
        }
    }


    public function logout(){
        if (Auth::guard('api')->user()->token()->revoke()){
            return response()->json(['status' => "success", 'message' => 'Cerraste sesion correctamente'], $this->successStatus);
        }else{
            return response()->json(['error' => "success", 'message' => 'Cerraste sesion correctamente'], $this->successStatus);
        }
    }

}
