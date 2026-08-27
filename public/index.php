<?php

use Core\Router;
use Core\Session;

const BASE_PATH = __DIR__ . '/../';

session_start();

require BASE_PATH . 'Core/functions.php';
require BASE_PATH . 'autoload.php';
require BASE_PATH . 'bootstrap.php';

$router = new Router();
require BASE_PATH . 'routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && in_array($_POST['_method'] ?? '', ['PUT', 'PATCH', 'DELETE'], true)) {
    $method = $_POST['_method'];
}

$router->route($uri, $method);
Session::unflash();
