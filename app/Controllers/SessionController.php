<?php

namespace App\Controllers;

use App\Models\User;
use Core\App;
use Core\Authenticator;
use Core\Database;
use Core\Session;
use Core\Validator;

class SessionController
{
    private Authenticator $authenticator;

    public function __construct()
    {
        $this->authenticator = new Authenticator();
    }

    public function create(): void
    {
        view('sessions/create.php');
    }

    public function store(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if (!Validator::string($email, 1, 100) || !Validator::email($email)) {
            $errors['email'] = 'Informe um email valido.';
        }

        if (!Validator::string($password, 1, 255)) {
            $errors['password'] = 'Informe uma senha válida.';
        }

        if ($errors) {
            $this->renderInvalidLogin($errors, is_string($email) ? $email : '');
            return;
        }

        if (!$this->authenticator->attempt($email, $password)) {
            $this->renderInvalidLogin(
                ['credentials' => 'Ops, Email ou Senha inválido'],
                $email
            );
            return;
        }

        redirect('/');
    }

    public function destroy(): void
    {
        $this->authenticator->logout();
        redirect('/login');
    }

    private function renderInvalidLogin(array $errors, string $email): void
    {
        http_response_code(422);
        view('sessions/create.php', [
            'errors' => $errors,
            'old' => ['email' => $email],
        ]);
    }
}
