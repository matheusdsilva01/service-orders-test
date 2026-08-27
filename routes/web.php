<?php

use App\Controllers\PageController;
use App\Controllers\SessionController;
use App\Controllers\UserController;

$router->get('/', [PageController::class, 'home'])->only('auth');

$router->get('/login', [SessionController::class, 'create'])->only('guest');
$router->post('/login', [SessionController::class, 'store'])->only('guest');
$router->delete('/logout', [SessionController::class, 'destroy'])->only('auth');

$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users', [UserController::class, 'store']);
