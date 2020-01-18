<?php

namespace App\Http\Controllers\Api;

use App\model\Group;
use App\model\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    //
    public $successStatus = 200;


    public function createGroups(Request $request){
        $validator = Validator::make(request()->all(), [

            'name' => ['required'],
            'desc' => ['required'],
            'color' => ['required'],
            'code' => ['required', 'min:6', 'unique:groups'],

        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = Auth::guard('api')->user();

        $group = new Group();
        $group->id_creator = $user->id;
        $group->name = $request->name;
        $group->desc = $request->desc;
        $group->color = $request->color;
        $group->code = $request->code;
        $group->status = true;

        if ($group->save()){
            return response()->json(['status' => "success", 'message'=>'Creaste un grupo correctamente'], $this->successStatus);

        }else{
            return response()->json(['status' => "error", 'message'=>'Ocurrio un error al intentar crear el grupo, vuelve a intentarlo'], $this->successStatus);

        }



    }

    public function joinGroups(Request $request){
        $validator = Validator::make(request()->all(), [
            'id_user' => ['required'],
            'code' => ['required', 'min:6'],

        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], $this->successStatus);
        }

        $group = Group::where('code', $request->code)->first();

        if ($group != null){


            if(Member::where('id_group', $group->id)->where('id_user', $request->id_user)->exists()){
                return response()->json(['status' => "error", 'message'=>'Ocurrio un error al intentar unirte al grupo, es posible que ya seas parte de este grupo'], $this->successStatus);

            }else{

                $member = new Member();
                $member->id_group = $group->id;
                $member->id_user = $request->id_user;

                if ($member->save()){
                    return response()->json(['status' => "success", 'message'=>'Te haz unido con exito a este grupo'], $this->successStatus);

                }else{
                    return response()->json(['status' => "error", 'message'=>'Ocurrio un error al intentar unirte a este grupo'], $this->successStatus);

                }




            }



        }else{
            //return response()->json(['status' => "error", 'message'=>'Ocurrio un error al intentar unirte al grupo, vuelve a intentarlo mas tarde'], $this->successStatus);
            return response()->json(['status' => "error", 'message'=>'Ocurrio un error al intentar unirte al grupo, es posible que el codigo de grupo no sea correcto'], $this->successStatus);

        }


    }

    public function myGroupsTeacher(Request $request){
        $user = Auth::guard('api')->user();
        $groups = Group::where('id_creator', $user->id)->orderBy('created_at','desc')->get();

        return response()->json(['statusResponse' => 'success', 'message' => 'Datos obtenido correctamente' , 'groups' => $groups], $this->successStatus);

    }

    public function membersGroups(Request $request){
        $validator = Validator::make(request()->all(), [
            'id_grupo' => ['required'],
        ]);

        $members = Member::where('id_group', $request->id_group)->orderBy('created_at')->with('user')->get();

        return response()->json(['status' =>'success', 'message' => 'Datos obtenidos correctamente', 'members' => $members], $this->successStatus);

    }

    public function myGroups(Request $request){

    }



    public function exitGroups(Request $request){

    }

}
