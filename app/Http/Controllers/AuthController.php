<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Trez\RayganSms\Facades\RayganSms;

class AuthController extends Controller
{
    public function register(Request $request){

            $field = $request->validate([
                'username' => ['required','min:3'],
                'password' => ['required','confirmed','min:6'],
                'phone' => ['required','unique:users','numeric','min:10','regex:/(09)[0-9]{9}/'],
            ]);

            $user = User::create([
                'username' =>$field['username'],
                'password' =>bcrypt($field['password']),
                'phone' =>$field['phone'],
                'address' =>$request['address'],
                'name' =>$request['name'],
                'lastname' =>$request['lastname'],
            ]);

            $token =  $user->createToken('jahadi')->plainTextToken;

            $response = [
                'username' => $user,
                'token'=>$token,
            ];
            RayganSms::sendMessage('09927274200','Welcome');
            return Response($response,201);

    }


    public function login(Request $request){
        $field = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username',$field['username'])->first();

        if(!$user) {
            return response([
                'message' => 'user not found'
            ], 401);
        }
        if(!Hash::check($field['password'], $user->password)){
            return response([
                'message'=>'password not correct'
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = ['username'=>$user,'token'=>$token];
        return Response($response,201);
    }


    public function update(Request $request,$id){
        if($id != auth()->id()){
            return Response(['message'=>'access denied',],403);
        }else{
            $user = User::find($id);
            if($request['address']){
                $user->address = $request['address'];
                $user->save();
            }
            if($request['name']){
                $user->name = $request['name'];
                $user->save();
            }
            if($request['lastname']){
                $user->lastname = $request['lastname'];
                $user->save();
            }
            if($request['phone']){
                $user->phone = $request['phone'];
                $user->save();
            }
            if($request['phone']){
                $user->phone = $request['phone'];
                $user->save();
            }
            if($request['lat']){
                $user->lat = $request['lat'];
                $user->save();
            }
            if($request['long']){
                $user->long = $request['long'];
                $user->save();
            }
            $response = ['user'=>$user,'message'=>'user update success'];
            return Response($response,201);
        }
    }


    public function sendrequest(Request $request,$id){
        if($id != auth()->id()){
            return Response(['message'=>'access denied',],403);
        }else{
            $user = User::find($id);
            $user->call = 1;
            $user->save();
            $response = ['message'=>'call success'];
            return Response($response,201);}
    }


    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
