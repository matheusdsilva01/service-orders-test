<?php

namespace Core;

class App
{
    private static Container $container;

    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    public static function container(): Container
    {
        return self::$container;
    }

    public static function resolve(string $key): mixed
    {
        return self::container()->resolve($key);
    }
}
