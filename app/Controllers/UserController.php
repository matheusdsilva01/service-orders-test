<?php

namespace App\Controllers;

use App\DTOs\CreateUserData;
use App\Models\User;
use Core\App;
use Core\Database;
use Core\Validator;
use PDOException;

class UserController
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::resolve(Database::class);
    }

    public function store(): void
    {
        $errors = [];
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!Validator::string($name, 1, 150)) {
            $errors['name'] = 'Nome inválido. O nome deve ter entre 1 e 100 caracteres.';
        }

        if (!Validator::string($email, 1, 100)) {
            $errors['email'] = 'Email inválido. O email deve ter entre 1 e 255 caracteres.';
        } elseif (!Validator::email($email)) {
            $errors['email'] = 'Formato de email inválido';
        }

        if (!Validator::string($password, 8, 45)) {
            $errors['password'] = 'Senha é obrigatoria e deve ter entre 8 e 45 caracteres.';
        }

        if (!empty($errors)) {
            http_response_code(422);
            view('users/create.php', [
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);
            return;
        }

        $userModel = new User($this->database);
        $userData = new CreateUserData($name, $email, $password);

        try {
            $userModel->create($userData);
        } catch (PDOException $exception) {
            if ($exception->errorInfo[1] === 1062) {
                http_response_code(422);
                view('users/create.php', [
                    'errors' => [
                        'email' => 'Este email ja esta cadastrado.',
                    ],
                    'old' => [
                        'name' => $name,
                        'email' => $email,
                    ],
                ]);
                return;
            }
            throw $exception;
        }

        redirect('/');
    }

    public function create(): void
    {
        view('users/create.php');
    }
}
