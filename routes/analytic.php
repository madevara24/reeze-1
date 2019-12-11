<?php

use Illuminate\Http\Request;

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

Route::prefix('/v1')->group(function(){
    Route::prefix('/analytic')->namespace('Api')->group(function(){
        Route::namespace('Analytic')->group(function(){
            Route::get('/sprint-progrssion/{id}', 'SprintProgressionController@show');
        });
    });
});