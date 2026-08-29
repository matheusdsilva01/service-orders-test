<?php

use Core\App;
use Core\Container;
use Core\Database;

$container = new Container();

$config = require base_path('config.php');

date_default_timezone_set($config['app']['timezone']);

$container->bind(Database::class, function (): Database {
    $config = require base_path('config.php');

    return new Database($config['database']);
});

App::setContainer($container);
