<?php

use App\Controllers\PageController;
use App\Controllers\UserController;

$router->get('/', [PageController::class, 'home']);

$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users', [UserController::class, 'store']);
