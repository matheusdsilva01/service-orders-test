<?php

namespace App\Models;

use App\DTOs\CreateServiceData;
use App\DTOs\UpdateServiceData;
use Core\Database;

class Service
{
    public function __construct(
        private Database $database
    )
    {
    }

    public function all(array $filters): array
    {
        $startDate = $filters['startDate'] !== '' ? $filters['startDate'] : null;
        $endDate = $filters['endDate'] !== '' ? $filters['endDate'] : null;
        $status = $filters['status'] !== '' ? $filters['status'] : null;
        $serviceName = $filters['serviceName'] !== '' ? $filters['serviceName'] : null;
        $userName = $filters['userName'] !== '' ? $filters['userName'] : null;

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
                    WHERE 1=1
                        AND (:start_date IS NULL OR s.created_at >= :start_date)
                        AND (:end_date IS NULL OR s.created_at < DATE_ADD(:end_date, INTERVAL 1 DAY))
                        AND (:status IS NULL OR (:status = 'pending' AND s.finished_at IS NULL) OR (:status = 'finished' AND s.finished_at IS NOT NULL))
                        AND (:service_name IS NULL OR LOWER(s.description) LIKE LOWER(CONCAT('%', :service_name, '%')))
                        AND (:user_name IS NULL OR LOWER(u.name) LIKE LOWER(CONCAT('%', :user_name, '%')))
                    ORDER BY s.created_at DESC, s.id_service DESC
                SQL,
                [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status,
                    'service_name' => $serviceName,
                    'user_name' => $userName,
                ]
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

    public function update(int $id, UpdateServiceData $data): void
    {
        $this->database->query(
            <<<'SQL'
                UPDATE service
                SET description = :description,
                    price = :price
                WHERE id_service = :id
            SQL,
            [
                'id' => $id,
                'description' => $data->description,
                'price' => $data->price
            ]
        );
    }

    public function totalPriceForUser(int $userId): string
    {
        $result = $this->database
            ->query(
                <<<'SQL'
                SELECT COALESCE(SUM(price), 0.000) AS total
                FROM service
                WHERE user_id_user = :user_id
            SQL,
                ['user_id' => $userId]
            )
            ->find();

        return $result['total'];
    }

    public function latestPendingForUser(int $userId): array
    {
        return $this->database
            ->query(
                <<<'SQL'
                SELECT
                    id_service,
                    description,
                    price,
                    created_at
                FROM service
                WHERE user_id_user = :user_id
                  AND finished_at IS NULL
                ORDER BY created_at DESC, id_service DESC
                LIMIT 3
            SQL,
                ['user_id' => $userId]
            )
            ->get();
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

    public function finish(int $id, string $commission): void
    {
        $this->database->query(
            <<<'SQL'
                UPDATE service
                SET finished_at = NOW(),
                    commission_user = :commission
                WHERE id_service = :id
            SQL,
            [
                'id' => $id,
                'commission' => $commission
            ]
        );
    }
}