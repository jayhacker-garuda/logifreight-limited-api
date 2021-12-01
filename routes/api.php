<?php

use App\Http\Controllers\API\v1\MemberController;
use App\Http\Controllers\API\v1\QuickAlertController;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->prefix('member')->group(function(){
    Route::get('/info', function (Request $request){
        return $request->user();
    });

    Route::post('/quick-alert', [QuickAlertController::class, 'AddQuickAlert']);
    Route::get('/packages', [QuickAlertController::class, 'packages']);
    Route::get('/logout', [MemberController::class, 'logout']);
});

Route::post('/member/register', [MemberController::class, 'registration']);
Route::post('/member/login', [MemberController::class, 'login']);
