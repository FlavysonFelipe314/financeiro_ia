<?php

use App\Http\Controllers\Api\v1\EntradaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/', function(){
    return response()->json([
        'status' => true,
        'message' => 'running'
    ], 200);
});

Route::apiResource('entrada', EntradaController::class);