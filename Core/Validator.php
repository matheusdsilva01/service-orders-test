<?php

namespace Core;

use DateTimeImmutable;

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

    public static function number(mixed $value, int|float $min = -INF, int|float $max = INF): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        return $value >= $min && $value <= $max;
    }

    public static function positiveInteger(mixed $value, int $min = 1, int $max = PHP_INT_MAX): bool
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        return $integer !== false && $integer >= $min && $integer <= $max;
    }

    public static function date(mixed $value, string $format = 'Y-m-d'): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $date = DateTimeImmutable::createFromFormat("!{$format}", $value);

        return $date !== false && $date->format($format) === $value;
    }
}
