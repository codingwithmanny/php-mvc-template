<?php
require dirname(__DIR__) . '/config/bootstrap.php';

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

/* ROUTES
-------------------------------------- */
$router->get('/users', ['App\Controllers\UsersController', 'index']);
$router->get('/users/new', ['App\Controllers\UsersController', 'create_form']);
$router->post('/users', ['App\Controllers\UsersController', 'create']);
//@TODO: delete
//@TODO: update form
//@TODO: update

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

