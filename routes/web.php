<?php

use App\Controllers\PageController;

$router->get('/', [PageController::class, 'home']);
