<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// public api
Route::post('/register',[ AuthController::class , 'register']);
Route::post('/login',[AuthController::class , 'login']);

//admin api
Route::prefix('admin')->middleware(['admin.auth','auth:sanctum'])->group(function (){
    Route::get('/alluser', [ AdminController::class , 'alluser' ] );
    Route::get('/requestlist', [ AdminController::class , 'requestlist' ] );
    Route::post('/acceptrequest/{username}', [ AdminController::class , 'acceptrequest' ] );
});

// private api
Route::group(['middleware'=>['auth:sanctum','auth']], function () {
    Route::put('/userupdate/{username}',[AuthController::class , 'update']);
    Route::post('/sendrequest/{username}',[AuthController::class , 'sendrequest']);
    Route::post('/logout',[AuthController::class , 'logout']);
});
