<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['jwtverif']], function () {
    Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('member/add', [App\Http\Controllers\MemberController::class, 'createMember']);
    Route::post('member/detail', [App\Http\Controllers\MemberController::class, 'showMember']);
    Route::put('member/update', [App\Http\Controllers\MemberController::class, 'updateMember']);
});
