<?php

namespace App\Models;

use App\DTOs\CreateServiceData;
use Core\Database;

class Service
{
    public function __construct(
        private Database $database
    )
    {
    }

    public function all(): array
    {
        return $this->database
            ->query(
                <<<'SQL'
                    SELECT
                        s.id_service,
                        s.description,
                        s.price,
                        s.created_at,
                        s.update_at,
                        s.finished_at,
                        u.name as user_name,
                        CASE
                            WHEN finished_at IS NULL THEN 'Pendente'
                            ELSE 'Finalizado'
                        END AS status
                    FROM service as s
                    INNER JOIN `user` AS u ON u.id_user = s.user_id_user
                SQL
            )
            ->get();
    }

    public function find(int $id): array
    {
        return $this->database
            ->query(
                <<<'SQL'
                    SELECT
                        id_service,
                        description,
                        price,
                        created_at,
                        update_at,
                        finished_at,
                        commission_user,
                        user_id_user,
                        CASE
                            WHEN finished_at IS NULL THEN 'Pendente'
                            ELSE 'Finalizado'
                        END AS status
                    FROM service
                    WHERE id_service = :id
                SQL,
                ['id' => $id]
            )
            ->findOrFail();
    }

    public function existsByDescriptionForUser(
        string $description,
        int    $userId
    ): bool
    {
        return $this->database
                ->query(
                    <<<'SQL'
                    SELECT 1
                    FROM service
                    WHERE description = :description
                      AND user_id_user = :user_id
                    LIMIT 1
                SQL,
                    [
                        'description' => $description,
                        'user_id' => $userId,
                    ]
                )
                ->find() !== false;
    }

    public function create(CreateServiceData $data): void
    {
        $this->database->query(
            <<<'SQL'
                INSERT INTO service (
                    description,
                    price,
                    user_id_user
                ) VALUES (
                    :description,
                    :price,
                    :user_id
                )
            SQL,
            [
                'description' => $data->description,
                'price' => $data->price,
                'user_id' => $data->userId,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->database->query(
            <<<'SQL'
                DELETE FROM service
                WHERE id_service = :id
            SQL,
            ['id' => $id]
        );
    }

    public function finish(int $id): void
    {
        $this->database->query(
            <<<'SQL'
                UPDATE service
                SET finished_at = NOW()
                WHERE id_service = :id
            SQL,
            ['id' => $id]
        );
    }
}