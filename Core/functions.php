<?php

use Core\Response;

function dd(mixed $value): never
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    exit;
}

function base_path(string $path = ''): string
{
    return BASE_PATH . $path;
}

function view_path(string $path): string
{
    return base_path('app/Views/' . $path);
}

function view(string $path, array $attributes = []): void
{
    extract($attributes);
    require view_path($path);
}

function redirect(string $path): never
{
    header("Location: {$path}");
    exit;
}

function abort(int $code = Response::NOT_FOUND): never
{
    http_response_code($code);
    require view_path("errors/{$code}.php");
    exit;
}

function authorize(bool $condition): void
{
    if (!$condition) {
        abort(Response::FORBIDDEN);
    }
}

function urlIs(string $path): bool
{
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === $path;
}

function escapeHtml(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function price_format(float $value): string
{
    return number_format($value, 2, ',', '.');
}