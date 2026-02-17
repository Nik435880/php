<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';

use Http\Controllers\UserController;
use Http\Controllers\AuthController;
use Http\Controllers\PostController;
use Core\Router;
use Core\Database;
use Core\Container;
use Core\Session;
use Dotenv\Dotenv;
use PDO;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();



$config = require __DIR__ . '/../config.php';

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}


function view($view, $data = [])
{
    extract($data);
    require_once __DIR__ . "/../views/{$view}.php";
}

function redirect($url, $status = 0)
{
    header("Location:" . $url, true, $status);
}



$container = new Container();
Container::setInstance($container);

$container->bind(PDO::class, function () use ($config) {
    $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'] . ';charset=' . $config['charset'];
    return new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
});

$container->bind(Database::class, function ($container) {
    return new Database($container->make(PDO::class));
});



$router = new Router();

$router->get('/', function () {
    return view('index.view', [
        'title' => "Home"
    ]);
}, 'auth');

$router->get('/users', [UserController::class, 'index'], 'auth');
$router->get('/register', [UserController::class, 'create'], 'guest');
$router->post('/register', [UserController::class, 'store'], 'guest');
$router->get('/users/{id}', [UserController::class, 'show'], 'auth');
$router->get('/login', [AuthController::class, 'create'], 'guest');
$router->post('/login', [AuthController::class, 'login'], 'guest');
$router->get('/logout', [AuthController::class, 'logout'], 'auth');
$router->get('/posts', [PostController::class, 'index'], 'auth');
$router->get('/posts/create', [PostController::class, 'create'], 'auth');
$router->post('/posts/create', [PostController::class, 'store'], 'auth');


$router->delete('/logout', [AuthController::class, 'logout'], 'auth');

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

$router->route($_SERVER['REQUEST_URI'], $method);
