<?php

namespace App\DTOs;

final readonly class UpdateServiceData
{
    public function __construct(
        public string $description,
        public float  $price,
    )
    {
    }
}