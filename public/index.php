<?php
require dirname(__DIR__) . '/config/bootstrap.php';

use Phroute\Phroute\RouteCollector;
use App\Controllers\Auth\JWTController;

$router = new RouteCollector();

/* ROUTES
-------------------------------------- */
//VIEWS
//users
$router->get('/users', ['App\Controllers\UsersController', 'index']);
$router->get('/users/create', ['App\Controllers\UsersController', 'create_form']);
$router->post('/users', ['App\Controllers\UsersController', 'create']);
$router->get('/users/{id}', ['App\Controllers\UsersController', 'read']);
$router->get('/users/{id}/delete', ['App\Controllers\UsersController', 'delete']);
$router->get('/users/{id}/edit', ['App\Controllers\UsersController', 'update_form']);
$router->post('/users/{id}/edit', ['App\Controllers\UsersController', 'update']);

//auth
$router->get('/auth/register', ['App\Controllers\Auth\AuthController', 'register_form']);
$router->post('/auth/register', ['App\Controllers\Auth\AuthController', 'register']);
$router->get('/auth/login', ['App\Controllers\Auth\AuthController', 'login_form']);
$router->post('/auth/login', ['App\Controllers\Auth\AuthController', 'login']);
$router->get('/auth/logout', ['App\Controllers\Auth\AuthController', 'logout']);


//admin
$router->group(['before' => 'auth'], function($router) {
    $router->get('/admin', ['App\Controllers\AdminController', 'dashboard']);
});

//API
$router->group(['prefix' => 'api'], function($router) {
    //users
    $router->get('/users', ['App\Controllers\UsersController', 'index']);
    $router->get('/users/{id}', ['App\Controllers\UsersController', 'read']);
    $router->post('/users', ['App\Controllers\UsersController', 'create']);
    $router->put('/users/{id}', ['App\Controllers\UsersController', 'update']);
    $router->delete('/users/{id}', ['App\Controllers\UsersController', 'delete']);

    //auth
    $router->post('/auth/register', ['App\Controllers\Auth\AuthController', 'register']);
    $router->post('/auth/login', ['App\Controllers\Auth\AuthController', 'login']);
});

/* FILTERS
-------------------------------------- */
$router->filter('auth', function() {
    $jwt = new JWTController();
    if($jwt->json_request()) {
        $headers = getallheaders();
        if(!array_key_exists('WEBTOKEN', $headers) || !$jwt->validate_token($headers['WEBTOKEN'])) {
            header('HTTP/1.1 401 Unauthorized');
            return false;
        }
    } else {
        if(!isset($_SESSION) || !array_key_exists('WEBTOKEN', $_SESSION) || !$jwt->validate_token($_SESSION['WEBTOKEN'])) {
            header('Location: /auth/login');
        }
    }
});

/* OUTPUT
-------------------------------------- */
$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());

try {
    $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    echo $response;
} catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
    header("HTTP/1.0 404 Not Found");
    die();
} catch (Phroute\Phroute\Exception\HttpMethodNotAllowedException $e) {
    header("HTTP/1.0 401 Not Found");
    die();
}

