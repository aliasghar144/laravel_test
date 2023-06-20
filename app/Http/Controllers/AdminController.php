<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\accepted_requests;
use App\Models\user;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function alluser(){
        return user::where('isadmin',0)->get();
    }

    public function deleteuser($id){
        return User::find($id)->delete();
    }

    public function acceptrequest($id){
        $user = user::find($id);
        if($user->request == 1){
            $user->request = 0;
            $user->save();

            $recived = accepted_requests::where('user_id',$id)->first();
            if($recived){
                $recived->count += 1;
                $recived->save();
            }else{
                $create = accepted_requests::create([
                    'user_id'=>$id
                ]);
            }

            $response = ['user'=>$user ,'message'=>'مواد بازیافتی دریافت شد'];

            return Response($response,201);}else{
            return Response(['message'=>'کاربر درخواستی را ثبت نکرده است'],401);
        }

    }

    public function activeuser($id){
        $user = user::find($id);
        if($user->isactive == 0){
            $user->isactive = 1;
            $user->save();

            $response = ['user'=>$user ,'message'=>'کاربر با موفقیت فعال شد'];

            return Response($response,201);}else{
            return Response(['message'=>'کاربر فعال است'],401);
        }

    }

    public function recived_list(){
        $list = collect();
        $recived = accepted_requests::all();
        foreach ($recived as $item){
           $user_id = $item->user_id;
           $user = user::find($user_id);
           $respone = ['id'=>$item->id , 'user'=>$user,'count'=>$item->count];
           $list->push($respone);
        }
                   return Response($list,200);

    }

    public function searchaddress($address){
        return User::where('address','like','%'.$address.'%')->get();
    }

    public function searchname($name){
        return User::where('name','like','%'.$name.'%')->orwhere('name','like','%'.$name.'%')->get();
    }
}
