<?php

use App\Contracts\Mailer;
use App\Services\NativeMailer;
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

$container->bind(Mailer::class, function (): Mailer {
    $config = require base_path('config.php');

    return new NativeMailer($config['mail']);
});

App::setContainer($container);
