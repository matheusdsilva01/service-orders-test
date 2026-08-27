<?php

namespace Core;

use App\Models\User;

class Authenticator
{
    public function attempt(string $email, string $password): bool
    {
        $users = new User(App::resolve(Database::class));
        $user = $users->findByEmail($email);

        if (!$user || !(bool)$user['ativo'] || !password_verify($password, $user['password'])) {
            return false;
        }

        $this->login($user);

        return true;
    }

    public function login(array $user): void
    {
        Session::regenerate();
        Session::put('user', [
            'id' => (int)$user['id_user'],
            'name' => $user['name'],
            'email' => $user['email'],
        ]);
    }

    public function logout(): void
    {
        Session::destroy();
    }
}