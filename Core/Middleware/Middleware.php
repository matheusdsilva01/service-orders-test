<?php

namespace Core\Middleware;

use RuntimeException;

class Middleware
{
    private const MAP = [
        'auth' => Authenticated::class,
        'guest' => Guest::class,
    ];

    public static function resolve(?string $key): void
    {
        if ($key === null) {
            return;
        }

        $middleware = self::MAP[$key] ?? null;

        if ($middleware === null) {
            throw new RuntimeException("Unknown middleware: {$key}.");
        }

        (new $middleware())->handle();
    }
}
