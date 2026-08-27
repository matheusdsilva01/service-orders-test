<?php

namespace Core;

use RuntimeException;

class Container
{
    private array $bindings = [];

    public function bind(string $key, callable $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve(string $key): mixed
    {
        if (!array_key_exists($key, $this->bindings)) {
            throw new RuntimeException("No binding found for {$key}.");
        }

        return ($this->bindings[$key])();
    }
}
