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
Route::prefix('v1')->group(function(){
    Route::post('login', 'Api\AuthController@loginUser');
    Route::post('register', 'Api\AuthController@registerUser');
    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('logout', 'Api\AuthController@logout');

        /**
         * Groups
         */
        Route::post('create/group', 'Api\GroupController@createGroups');
        Route::post('join/group', 'Api\GroupController@joinGroups');


        Route::post('my/groups/teacher', 'Api\GroupController@myGroupsTeacher');
        Route::post('my/groups/student', 'Api\GroupController@myGroupsStudent');

        Route::post('exit/group', 'Api\GroupController@exitGroups');
        Route::post('close/group', 'Api\GroupController@closeGroups');

        Route::post('tests', 'Api\GroupController@myTest');
        Route::post('test/create', 'Api\GroupController@createTest');

    });
});
