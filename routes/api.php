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

// Route::prefix('/v1')->middleware('web')->group(function(){
Route::prefix('/v1')->middleware('api')->namespace('Api')->group(function(){
    Route::get('/login', 'ApiController@redirectToProvider')->name('api-login');
    Route::get('/github-callback', 'ApiController@handleProviderCallback');

    Route::middleware('jwt.auth')->group(function(){
        Route::get('/list-repo', 'ProjectController@getListRepository');
        Route::get('/logout', 'ApiController@logout');
        Route::prefix('/project')->group(function(){
            Route::get('/', 'ProjectController@index');
            Route::post('/create', 'ProjectController@store');

            Route::middleware('user.project')->group(function(){
                Route::get('/{project_id}/members', 'ProjectController@member');
                Route::put('/{project_id}/edit', 'ProjectController@update');
                Route::delete('/{project_id}/delete', 'ProjectController@destroy');

                Route::get('{project_id}/cards', 'CardController@index');
                Route::post('{project_id}/card/create', 'CardController@store');
                Route::put('{project_id}/card/{card_id}/edit', 'CardController@update');
                Route::delete('{project_id}/card/{card_id}/delete', 'CardController@destroy');

                Route::post('{project_id}/card/{card_id}/create-branch', 'CardController@createGithubBranch');
                Route::post('{project_id}/card/{card_id}/update-state', 'CardController@updateCardState');
            });
        });
    });
});
