<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group(['prefix' => 'api'], function () use ($router){
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');

    $router->post('scan', 'AttendancesController@scan');
    $router->get('scan', 'AttendancesController@getData');
    $router->get('scan/{email}', 'AttendancesController@getQR');

});
