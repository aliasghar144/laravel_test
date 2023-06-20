<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use function Webmozart\Assert\Tests\StaticAnalysis\isEmptyString;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $field = $request->validate([
            'username' => ['required', 'unique:users', 'min:3'],
            'password' => ['required', 'confirmed', 'min:6'],
            'phone' => ['required', 'unique:users', 'min:10',],
        ],
            [
                'username.unique' => 'نام کاربری قبلا ثبت شده است',
                'phone.unique' => 'شماره موبایل قبلا ثبت شده است'
            ]);

        $user = User::create([
            'username' => $field['username'],
            'password' => bcrypt($field['password']),
            'phone' => $field['phone'],
            'address' => $request['address'],
            'name' => $request['name'],
            'request' => 0,
            'lastname' => $request['lastname'],
            'lat' => $request['lat'],
            'long' => $request['long'],
        ]);

        $token = $user->createToken('jahadi')->plainTextToken;

        $user = User::where('username', $field['username'])->first();
        $response = [
            'username' => $user,
            'token' => $token,
        ];
        return Response($response, 201);

    }

    public function adminregister(Request $request)
    {

        $field = $request->validate([
            'username' => ['required', 'unique:users', 'min:3'],
            'password' => ['required', 'confirmed', 'min:6'],
            'phone' => ['required', 'unique:users', 'min:10',],
        ],
            [
                'username.unique' => 'نام کاربری قبلا ثبت شده است',
                'phone.unique' => 'شماره موبایل قبلا ثبت شده است'
            ]);

        $user = User::create([
            'username' => $field['username'],
            'password' => bcrypt($field['password']),
            'phone' => $field['phone'],
            'name' => $request['name'],
            'request' => 0,
            'isadmin'=>1,
        ]);

        $token = $user->createToken('jahadi')->plainTextToken;

        $user = User::where('username', $field['username'])->first();
        $response = [
            'username' => $user,
            'token' => $token,
        ];
        return Response($response, 201);

    }


    public function login(Request $request)
    {
        $field = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $field['username'])->first();

        if (!$user) {
            return response([
                'message' => 'کاربر یافت نشد',
            ], 401);
        }
        if (!Hash::check($field['password'], $user->password)) {
            return response([
                'message' => 'رمز عبور وارد شده اشتباه است',
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = ['username' => $user, 'token' => $token];
        return Response($response, 201);
    }


    public function update(Request $request, $id)
    {
        if ($id != auth()->id()) {
            return Response(['message' => 'دسترسی غیر مجاز',], 403);
        } else {
            $user = User::find($id);
            if ($user->phone == $request['phone'] || $request['phone'] == null) {
                return $this->extracted($id, $request);
            } else {
                $field = $request->validate([
                    'phone' => ['unique:users'],
                ],
                    [
                        'phone.unique' => 'این شماره موبایل قبلا ثبت شده است.'
                    ]);
                return $this->extracted($id, $request);
            }
        }
    }


    public function sendrequest($id)
    {
        if ($id != auth()->id()) {
            return Response(['message' => 'دسترسی غیر مجاز',], 403);
        } else {
            $user = User::find($id);
            $user->request = 1;
            $user->save();
            $response = ['message' => 'درخواست ثبت شد'];
            return Response($response, 201);
        }
    }


    public function cancelrequest($id)
    {
        if ($id != auth()->id()) {
            return Response(['message' => 'دسترسی غیر مجاز',], 403);
        } else {
            $user = User::find($id);
            $user->request = 0;
            $user->save();
            $response = ['message' => 'درخواست ثبت شد'];
            return Response($response, 201);
        }
    }

    public function requeststatus($id)
    {
        if ($id != auth()->id()) {
            return Response(['message' => 'دسترسی غیر مجاز',], 403);
        } else {
            $user = User::find($id);
            $response = ['request'=>$user->request , 'isactive'=>$user->isactive];
            return Response($response, 201);
        }
    }


    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'خروج با موفقیت انجام شد'
        ];
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function extracted($id, Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|Response|\Illuminate\Foundation\Application
    {
        $user = User::find($id);
        if ($request['address']) {
            $user->address = $request['address'];
            $user->save();
        }
        if ($request['name']) {
            $user->name = $request['name'];
            $user->save();
        }
        if ($request['phone']) {
            $user->phone = $request['phone'];
            $user->save();
        }
        if ($request['lastname']) {
            $user->lastname = $request['lastname'];
            $user->save();
        }
        if ($request['lat']) {
            $user->lat = $request['lat'];
            $user->save();
        }
        if ($request['long']) {
            $user->long = $request['long'];
            $user->save();
        }
        $response = ['username' => $user, 'message' => 'کاربر با موفقیت بروزرسانی شد'];
        return Response($response, 201);
    }
}
