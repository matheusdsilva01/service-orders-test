<?php

use App\DTOs\CreateServiceData;
use App\DTOs\CreateUserData;
use App\Models\Service;
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

$services = [
    [
        'description' => 'Troca de tela de notebook',
        'price' => '425.000',
        'user_email' => 'jane.doe@example.com',
    ],
    [
        'description' => 'Instalacao de sistema operacional',
        'price' => '1500.000',
        'user_email' => 'john.doe@example.com',
    ],
    [
        'description' => 'Manutencao de servidor',
        'price' => '12500.000',
        'user_email' => 'm@email.com',
    ],
];

try {
    $database = App::resolve(Database::class);
    $userModel = new User($database);
    $serviceModel = new Service($database);

    $usersByEmail = [];
    $usersInserted = 0;
    $usersSkipped = 0;
    $servicesInserted = 0;
    $servicesSkipped = 0;

    foreach ($users as $user) {
        if ($userModel->existsByEmail($user['email'])) {
            $usersSkipped++;
        } else {
            $userModel->create(
                new CreateUserData(
                    name: $user['name'],
                    email: $user['email'],
                    password: $user['password'],
                )
            );

            $usersInserted++;
        }
        $storedUser = $userModel->findByEmail($user['email']);

        if (!$storedUser) {
            throw new RuntimeException(
                "Usuario {$user['email']} nao encontrado."
            );
        }

        $usersByEmail[$user['email']] = $storedUser;
    }

    foreach ($services as $service) {
        $user = $usersByEmail[$service['user_email']];
        $userId = (int)$user['id_user'];

        if (
            $serviceModel->existsByDescriptionForUser(
                $service['description'],
                $userId
            )
        ) {
            $servicesSkipped++;
            continue;
        }

        $serviceModel->create(
            new CreateServiceData(
                description: $service['description'],
                price: $service['price'],
                userId: $userId,
            )
        );

        $servicesInserted++;
    }

    echo "Usuarios: {$usersInserted} inserido(s), "
        . "{$usersSkipped} ignorado(s).\n";

    echo "Servicos: {$servicesInserted} inserido(s), "
        . "{$servicesSkipped} ignorado(s).\n";
} catch (Throwable $exception) {
    fwrite(
        STDERR,
        "Erro ao executar seed: {$exception->getMessage()}\n"
    );

    exit(1);
}
