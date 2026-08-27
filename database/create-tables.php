<?php

use Core\App;
use Core\Database;

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script deve ser executado via CLI.\n");
}

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . 'autoload.php';
require BASE_PATH . 'Core/functions.php';
require BASE_PATH . 'bootstrap.php';

try {
    $database = App::resolve(Database::class);

    $database->query(
        'CREATE TABLE IF NOT EXISTS user (
                id_user BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                update_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,
                ativo BOOLEAN NOT NULL DEFAULT TRUE
            )'
    );

    echo "Tabela user criada com sucesso.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, "Erro ao criar tabelas: {$exception->getMessage()}\n");
    exit(1);
}
