<?php

namespace App\Models;

use App\DTOs\CreateUserData;
use Core\Database;

class User
{
    public function __construct(
        private Database $database
    ) {
    }

    public function find(int $id): array
    {
        return $this->database
            ->query('SELECT * FROM user WHERE id_user = :id', [
                'id' => $id,
            ])
            ->findOrFail();
    }

    public function existsByEmail(string $email): bool
    {
        return $this->database
            ->query('SELECT 1 FROM user WHERE email = :email', [
                'email' => $email,
            ])
            ->find() !== false;
    }

    public function findByEmail(string $email): array|false
    {
        return $this->database
            ->query('
                    SELECT id_user, name, email, password, ativo
                    FROM user
                    WHERE email = :email
                    LIMIT 1',
                ['email' => $email]
            )
            ->find();
    }

    public function create(CreateUserData $attributes): void
    {
        $this->database->query(
            'INSERT INTO user (name, email, password) VALUES (:name, :email, :password)',
            [
                'name' => $attributes->name,
                'email' => $attributes->email,
                'password' => password_hash($attributes->password, PASSWORD_BCRYPT),
            ]
        );
    }
}
