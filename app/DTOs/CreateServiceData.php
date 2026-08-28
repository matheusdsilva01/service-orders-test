<?php

namespace App\DTOs;

final readonly class CreateServiceData
{
    public function __construct(
        public string $description,
        public string $price,
        public int    $userId,
    )
    {
    }
}