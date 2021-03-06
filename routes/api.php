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
Route::prefix('/v1')->middleware('api')->namespace('Api')->group(function () {
    Route::post('/login', 'ApiController@redirectToProvider')->name('api-login');
    Route::get('/github-callback', 'ApiController@handleProviderCallback');

    Route::middleware('jwt.auth')->group(function () {
        Route::get('/list-repo', 'ProjectController@getListRepository');

        Route::post('/search', 'ProjectController@searchMember');
        Route::post('/logout', 'ApiController@logout');

        Route::prefix('/user')->group(function () {
            Route::get('/', 'UserController@getAuthUser');
            Route::get('/{user_id}', 'UserController@show');
        });
        Route::prefix('/project')->group(function () {
            Route::get('/', 'ProjectController@index');
            Route::post('/create', 'ProjectController@store');

            Route::middleware('user.project')->group(function () {
                Route::get('/{project_id}', 'ProjectController@show');
                Route::get('/{project_id}/members', 'ProjectController@member');
                Route::get('/{project_id}/log', 'ProjectController@log');
                Route::put('/{project_id}/edit', 'ProjectController@update');
                Route::delete('/{project_id}/delete', 'ProjectController@destroy');
                Route::delete('/{project_id}/remove-member', 'ProjectController@removeMember');
                Route::post('/{project_id}/release', 'ProjectController@release');
                Route::post('/{project_id}/add-members', 'ProjectController@addMember');

                Route::get('{project_id}/cards', 'CardController@index');
                Route::get('{project_id}/cards/accepted', 'CardController@showCardReadyToRelease');
                Route::post('{project_id}/card/create', 'CardController@store');
                Route::put('{project_id}/card/{card_id}/edit', 'CardController@update');
                Route::delete('{project_id}/card/{card_id}/delete', 'CardController@destroy');

                Route::post('{project_id}/card/{card_id}/create-branch', 'CardController@createGithubBranch');
                Route::post('{project_id}/card/{card_id}/update-state', 'CardController@updateCardState');

                Route::prefix('/{project_id}/analytic')->namespace('Analytic')->group(function () {
                    Route::get('/current-sprint-dates', 'AnalyticController@currentSprintDates');
                    Route::get('/sprint-dates', 'AnalyticController@sprintDates');
                    Route::get('/sprint-day', 'AnalyticController@currentSprintDay');
                    Route::get('/sprint-progression/{user_id?}', 'AnalyticController@sprintProgression');
                    Route::get('/deliverability/{user_id?}', 'AnalyticController@deliverability');
                    Route::get('/rejection/{user_id?}', 'AnalyticController@rejection');
                    Route::get('/task-lifecycle/{user_id?}', 'AnalyticController@taskLifecycle');
                    Route::get('/estimation', 'AnalyticController@estimate');
                    Route::get('/card-timeline/{user_id?}', 'AnalyticController@cardTimeline');
                });
            });
        });
    });
});
