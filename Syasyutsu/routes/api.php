<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Route::post('/test','App\Http\Controllers\Downloader@test');
//Route::get('/test','App\Http\Controllers\Downloader@test');
