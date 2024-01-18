<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/items', [ItemController::class,'index']);
Route::post('/item', [ItemController::class,'store']);
Route::get('/item/{id}', [ItemController::class,'show']);
Route::put('/item/{id}', [ItemController::class,'update']);
Route::delete('/item/{id}', [ItemController::class,'destroy']);





