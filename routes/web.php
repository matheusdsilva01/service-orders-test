<?php

use App\Controllers\PageController;
use App\Controllers\ServiceController;
use App\Controllers\SessionController;
use App\Controllers\UserController;

$router->get('/', [PageController::class, 'home'])->only('auth');

$router->get('/login', [SessionController::class, 'create'])->only('guest');
$router->post('/login', [SessionController::class, 'store'])->only('guest');
$router->delete('/logout', [SessionController::class, 'destroy'])->only('auth');

$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users', [UserController::class, 'store']);

$router->get('/services/{id}/edit', [ServiceController::class, 'edit'])->only('auth');
$router->get('/services/create', [ServiceController::class, 'create'])->only('auth');
$router->post('/services', [ServiceController::class, 'store'])->only('auth');

$router->patch('/services/{id}', [ServiceController::class, 'update'])->only('auth');
$router->delete('/services/{id}', [ServiceController::class, 'delete'])->only('auth');
$router->post('/services/{id}/finish', [ServiceController::class, 'finish'])->only('auth');