<?php

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

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->post('auth/login', 'App\Api\V1\Controllers\AuthController@login');
    $api->post('auth/signup', 'App\Api\V1\Controllers\AuthController@signup');
    $api->post('auth/recovery', 'App\Api\V1\Controllers\AuthController@recovery');
    $api->post('auth/reset', 'App\Api\V1\Controllers\AuthController@reset');
    // example of protected route
    $api->group(['middleware'=>'api.auth'],
        function ($api)   {
            $api->post('auth/me', 'App\Api\V1\Controllers\AuthController@me');
            $api->resource('users', 'App\Api\V1\Controllers\UserController');
            $api->resource('roles', 'App\Api\V1\Controllers\RoleController');
            $api->resource('permissions', 'App\Api\V1\Controllers\PermissionController');
            $api->resource('permissions-groups', 'App\Api\V1\Controllers\PermissionGroupController');
        }
    );
});