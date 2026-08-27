<?php

namespace Core;

class Validator
{
    public static function string(mixed $value, int $min = 1, int|float $max = INF): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $length = strlen(trim($value));

        return $length >= $min && $length <= $max;
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
