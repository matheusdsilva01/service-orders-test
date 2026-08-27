<?php

use App\DTOs\CreateUserData;
use App\Models\User;
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

$users = [
    [
        'name' => 'Jane Doe',
        'email' => 'jane.doe@example.com',
        'password' => 'password',
    ],
    [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password',
    ],
    [
        'name' => 'Matheus silva',
        'email' => 'm@email.com',
        'password' => 'password',
    ],
];

try {
    $database = App::resolve(Database::class);
    $userModel = new User($database);
    $inserted = 0;
    $skipped = 0;

    foreach ($users as $user) {
        if ($userModel->existsByEmail($user['email'])) {
            $skipped++;
            continue;
        }

        $userModel->create(
            new CreateUserData(
                name: $user['name'],
                email: $user['email'],
                password: $user['password'],
            )
        );

        $inserted++;
    }

    echo "Seed concluido: {$inserted} inserido(s), {$skipped} ignorado(s).\n";
} catch (Throwable $exception) {
    fwrite(STDERR, "Erro ao executar seed: {$exception->getMessage()}\n");
    exit(1);
}
