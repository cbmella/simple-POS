<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar todas las rutas para una aplicación.
| Es muy fácil. Simplemente indica a Lumen las URIs a las que debe responder
| y dale el Closure a llamar cuando se solicite esa URI.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');
    $router->post('/logout', 'AuthController@logout');
    $router->post('/refresh', 'AuthController@refresh');
    $router->post('/me', 'AuthController@me');
    $router->get('/validate-token', 'AuthController@validateToken'); // Nuevo endpoint para validar el token
    $router->get('/test-token-ttl', 'AuthController@testTokenTTL');
    $router->get('/token-life', 'AuthController@tokenLife');
});

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/role/{role}', 'UserController@getUsersByRole');
    $router->get('/role/{role}/id/{id}', 'UserController@getUsersByRoleAndId');
});

$router->group(['prefix' => 'products'], function () use ($router) {
    $router->get('/', 'ProductController@index');           // Obtener todos los productos
    $router->get('/{id}', 'ProductController@show');        // Obtener un producto por ID
    $router->post('/', 'ProductController@store');          // Crear un nuevo producto
    $router->put('/{id}', 'ProductController@update');      // Actualizar un producto
    $router->delete('/{id}', 'ProductController@destroy');  // Eliminar un producto
});

$router->group(['prefix' => 'categories'], function () use ($router) {
    $router->get('/', 'CategoryController@index');           // Obtener todas las categorías
    $router->get('/{id}', 'CategoryController@show');        // Obtener una categoría por ID
    $router->post('/', 'CategoryController@store');          // Crear una nueva categoría
    $router->put('/{id}', 'CategoryController@update');      // Actualizar una categoría
    $router->delete('/{id}', 'CategoryController@destroy');  // Eliminar una categoría
});
