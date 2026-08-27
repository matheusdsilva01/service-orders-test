<?php

namespace App\Controllers;

use Core\Authenticator;
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
        $credentialsAreValid =
            Validator::string($email, 1, 100)
            && Validator::email($email)
            && Validator::string($password, 1, 255);

        if (!$credentialsAreValid || !$this->authenticator->attempt($email, $password)) {
            $this->renderInvalidLogin([
                'credentials' => 'Ops, Email ou Senha inválido'
            ], $email);
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
