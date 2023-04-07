<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\accepted_requests;
use App\Models\received;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function alluser(){
        return user::where('isadmin',0)->get();
    }

    public function requestlist(){
        return User::where('request',1)->get();
    }

    public function acceptrequest($id){
        $user = user::find($id);
        if($user->request == 1){
            $user->request = 0;
            $user->save();

            $call = accepted_requests::create([
                'user_id'=>$id
            ]);

            $response = ['user'=>$user ,'message'=>'recycle stuff recived'];

            return Response($response,400);}else{
            return Response(['message'=>'No request has been registered by the user'],201);
        }

    }

    public function recived_list(){}
}
