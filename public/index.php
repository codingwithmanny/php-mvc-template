<?php
require dirname(__DIR__) . '/config/bootstrap.php';

use App\Controllers\UsersController as Users;

$model = new Users();

$args = [
    'params' => [
        'page' => 1,
        'limit' => 100
    ]
];

echo '<hr/>';

echo '<h1>All</h1>';

echo '<pre>';
var_dump($model->all($args));
echo '</pre>';

echo '<hr/>';

echo '<h1>Create</h1>';

echo '<pre>';
//var_dump($model->create(['email' => 'sssss@asda.com', 'password' => '1234']));
echo '</pre>';

echo '<hr/>';

echo '<h1>Read: 2</h1>';

echo '<pre>';
var_dump($model->read(['where' => [['id', '=', 2]]]));
echo '</pre>';

echo '<hr/>';

echo '<h1>Update: 3</h1>';

echo '<pre>';
var_dump($model->update(['email' => 'aasa111s@asd.com'], ['where' => [['id', '=', 3]]]));
echo '</pre>';

echo '<hr/>';

echo '<h1>Delete: 2</h1>';

echo '<pre>';
var_dump($model->delete(['where' => [['id', '=', 1]]]));
echo '</pre>';

//use Phroute\Phroute\RouteCollector;
//use App\Controllers\JWTController;
//
//$router = new RouteCollector();
//
///* ROUTES
//-------------------------------------- */
//$router->any('/', ['App\Controllers\PageController', 'index']);
//
////private access
//$router->get('/dashboard', ['App\Controllers\PageController', 'dashboard']);
//
//$router->get('/admin', ['App\Controllers\PageController', 'admin']);
//$router->get('/admin/spaces', ['App\Controllers\PageController', 'admin_spaces']);
//$router->get('/admin/users', ['App\Controllers\PageController', 'admin_users']);
//$router->get('/admin/users/{id}', ['App\Controllers\PageController', 'admin_user']);
//$router->get('/admin/account', ['App\Controllers\PageController', 'admin_account']);
//
//$router->filter('auth', function() {
//    $jwt = new JWTController();
//    $headers = getallheaders();
//    if(!array_key_exists('WEBTOKEN', $headers)
//        || !$jwt->validateToken($headers['WEBTOKEN'])
//        || !array_key_exists('APPKEY', $headers)
//        || !$jwt->validateAppKey($headers['APPKEY'])) {
//        header('HTTP/1.1 401 Unauthorized');
//        return false;
//    }
//});
//
////public api
//$router->group(['prefix' => 'api'], function($router) {
//    //auth
//    $router->post('/auth/login', ['App\Controllers\APIAuthController', 'login']);
//    $router->post('/auth/register', ['App\Controllers\APIAuthController', 'register']);
//});
//
////private api
//$router->group(['prefix' => 'api', 'before' => 'auth'], function($router){
//    //auth
//    $router->get('/auth/self', ['App\Controllers\APIAuthController', 'self']);
//
//    //users
//    $router->get('/users', ['App\Controllers\APIUsersController', 'index']);
//    $router->post('/users', ['App\Controllers\APIUsersController', 'create']);
//    $router->get('/users/{id}', ['App\Controllers\APIUsersController', 'read']);
//    $router->put('/users/{id}', ['App\Controllers\APIUsersController', 'update']);
//    $router->delete('/users/{id}', ['App\Controllers\APIUsersController', 'delete']);
//});
//
////public pages
//$router->get('/login', ['App\Controllers\PageController', 'login_form']);
//$router->post('/login', ['App\Controllers\PageController', 'login']);
//$router->get('/logout', ['App\Controllers\PageController', 'logout']);
//$router->get('/register', ['App\Controllers\PageController', 'register_form']);
//$router->post('/register', ['App\Controllers\PageController', 'register_user']);
//$router->get('/spaces', ['App\Controllers\PageController', 'show_spaces']);
//$router->get('/spaces/{id}', ['App\Controllers\PageController', 'get_space']);
//$router->get('/{page}', ['App\Controllers\PageController', 'page']);
//
///* OUTPUT
//-------------------------------------- */
//$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
//
//try {
//    $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
//    echo $response;
//} catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
//    header("HTTP/1.0 404 Not Found");
//    die();
//} catch (Phroute\Phroute\Exception\HttpMethodNotAllowedException $e) {
//    header("HTTP/1.0 401 Not Found");
//    die();
//}



