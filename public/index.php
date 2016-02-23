<?php
require dirname(__DIR__) . '/config/bootstrap.php';

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();

/* ROUTES
-------------------------------------- */
$router->any('/', ['App\Controllers\UsersController', 'index']);

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

