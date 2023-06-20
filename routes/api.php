<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// public api
Route::post('/register',[ AuthController::class , 'register']);
Route::post('/adminregister',[ AuthController::class , 'adminregister']);
Route::post('/login',[AuthController::class , 'login']);
Route::get('/version',function (){
    return '1.0.0';
});

//admin api
Route::prefix('admin')->middleware(['admin.auth','auth:sanctum'])->group(function (){
    Route::get('/alluser', [ AdminController::class , 'alluser' ] );
    Route::post('/deleteuser/{username}', [ AdminController::class , 'deleteuser' ] );
    Route::get('/recivedlist', [ AdminController::class , 'recived_list' ] );
    Route::post('/activeuser/{username}', [ AdminController::class , 'activeuser' ] );
    Route::post('/acceptrequest/{username}', [ AdminController::class , 'acceptrequest' ] );
    Route::get('/serchaddress/{address}',[AdminController::class,'searchaddress']);
    Route::get('/serchname/{name}',[AdminController::class,'searchname']);
});

// private api
Route::group(['middleware'=>['auth:sanctum','auth']], function () {
    Route::post('/userupdate/{username}',[AuthController::class , 'update']);
    Route::post('/sendrequest/{username}',[AuthController::class , 'sendrequest']);
    Route::post('/cancelrequest/{username}',[AuthController::class , 'cancelrequest']);
    Route::post('/requeststatus/{username}',[AuthController::class , 'requeststatus']);
    Route::post('/logout',[AuthController::class , 'logout']);
});
