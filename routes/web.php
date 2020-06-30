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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/login', 'AuthController@postLogin');
$router->post('/register', 'AuthController@save');
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/alluser', 'AuthController@showAllUsers');
    $router->get('/currentUser', 'AuthController@currentUser');
    $router->get('/getUser/{id}', 'AuthController@showOneUsers');
    $router->put('/update/{id}', 'AuthController@update');
    $router->delete('/delete/{id}', 'AuthController@delete');
});

