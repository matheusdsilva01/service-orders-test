<?php

spl_autoload_register(function (string $class): void {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    require BASE_PATH . $file . '.php';
});
