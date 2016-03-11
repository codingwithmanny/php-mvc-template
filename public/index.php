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
$router->post('/users/{id}', ['App\Controllers\UsersController', 'update']);

//items
$router->get('/items', ['App\Controllers\ItemsController', 'index']);
$router->get('/items/create', ['App\Controllers\ItemsController', 'create_form']);
$router->post('/items', ['App\Controllers\ItemsController', 'create']);
$router->get('/items/{id}', ['App\Controllers\ItemsController', 'read']);
$router->get('/items/{id}/delete', ['App\Controllers\ItemsController', 'delete']);
$router->get('/items/{id}/edit', ['App\Controllers\ItemsController', 'update_form']);
$router->post('/items/{id}', ['App\Controllers\ItemsController', 'update']);

//user items
$router->get('/users/{user_id}/items', ['App\Controllers\UserItemsController', 'index']);

//tags
$router->get('/tags', ['App\Controllers\TagsController', 'index']);
$router->get('/tags/create', ['App\Controllers\TagsController', 'create_form']);
$router->post('/tags', ['App\Controllers\TagsController', 'create']);
$router->get('/tags/{id}', ['App\Controllers\TagsController', 'read']);
$router->get('/tags/{id}/delete', ['App\Controllers\TagsController', 'delete']);
$router->get('/tags/{id}/edit', ['App\Controllers\TagsController', 'update_form']);
$router->post('/tags/{id}', ['App\Controllers\TagsController', 'update']);

//item tags
$router->get('/itemstags/', ['App\Controllers\ItemsTagsController', 'index']);
$router->get('/itemstags/create', ['App\Controllers\ItemsTagsController', 'create_form']);
$router->post('/itemstags', ['App\Controllers\ItemsTagsController', 'create']);
$router->get('/itemstags/{id}', ['App\Controllers\ItemsTagsController', 'read']);
$router->get('/itemstags/{id}/delete', ['App\Controllers\ItemsTagsController', 'delete']);
$router->get('/itemstags/{id}/edit', ['App\Controllers\ItemsTagsController', 'update_form']);
$router->post('/itemstags/{id}', ['App\Controllers\ItemsTagsController', 'update']);



$router->get('/items/{id}/tags', ['App\Controllers\ItemsTagsController', 'itemstags']);


//auth
$router->get('/auth/register', ['App\Controllers\Auth\AuthController', 'register_form']);
$router->post('/auth/register', ['App\Controllers\Auth\AuthController', 'register']);
$router->get('/auth/login', ['App\Controllers\Auth\AuthController', 'login_form']);
$router->post('/auth/login', ['App\Controllers\Auth\AuthController', 'login']);
$router->get('/auth/logout', ['App\Controllers\Auth\AuthController', 'logout']);
$router->get('/auth/forgotpassword', ['App\Controllers\Auth\AuthController', 'forgot_form']);
$router->post('/auth/forgotpassword', ['App\Controllers\Auth\AuthController', 'forgot']);
$router->get('/auth/resetpassword/{token}', ['App\Controllers\Auth\AuthController', 'reset_form']);
$router->post('/auth/resetpassword/', ['App\Controllers\Auth\AuthController', 'reset']);

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
    $router->post('/auth/forgotpassword', ['App\Controllers\Auth\AuthController', 'forgot']);
    $router->post('/auth/resetpassword/', ['App\Controllers\Auth\AuthController', 'reset']);
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

