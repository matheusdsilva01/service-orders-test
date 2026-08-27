<?php

namespace Core;

use PDO;
use PDOStatement;

class Database
{
    private PDO $connection;
    private PDOStatement $statement;

    public function __construct(array $config)
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['charset']
        );

        $this->connection = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    public function query(string $query, array $parameters = []): self
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($parameters);

        return $this;
    }

    public function get(): array
    {
        return $this->statement->fetchAll();
    }

    public function find(): array|false
    {
        return $this->statement->fetch();
    }

    public function findOrFail(): array
    {
        $record = $this->find();

        if (!$record) {
            abort();
        }

        return $record;
    }
}
