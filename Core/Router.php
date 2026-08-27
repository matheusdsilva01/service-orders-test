<?php

namespace Core;

use Core\Middleware\Middleware;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $controller): self
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post(string $uri, array $controller): self
    {
        return $this->add('POST', $uri, $controller);
    }

    public function put(string $uri, array $controller): self
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function patch(string $uri, array $controller): self
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function delete(string $uri, array $controller): self
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function only(string $middleware): self
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $middleware;

        return $this;
    }

    public function route(string $uri, string $method): mixed
    {
        $uriMatched = false;

        foreach ($this->routes as $route) {
            $parameters = $this->match($route['uri'], $uri);

            if ($parameters === false) {
                continue;
            }

            $uriMatched = true;

            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            Middleware::resolve($route['middleware']);

            [$controller, $action] = $route['controller'];

            return (new $controller())->{$action}(...$parameters);
        }

        $this->abort($uriMatched ? Response::METHOD_NOT_ALLOWED : Response::NOT_FOUND);
    }

    private function add(string $method, string $uri, array $controller): self
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'middleware' => null,
        ];

        return $this;
    }

    private function match(string $routeUri, string $requestUri): array|false
    {
        $routeSegments = explode('/', $routeUri);
        $requestSegments = explode('/', $requestUri);

        if (count($routeSegments) !== count($requestSegments)) {
            return false;
        }

        $parameters = [];

        foreach ($routeSegments as $index => $routeSegment) {
            $requestSegment = $requestSegments[$index];

            $isParameter = preg_match(
                    '/^\{[a-zA-Z_][a-zA-Z0-9_]*\}$/',
                    $routeSegment
                ) === 1;

            if ($isParameter) {
                if ($requestSegment === '') {
                    return false;
                }

                $parameters[] = rawurldecode($requestSegment);
                continue;
            }

            if ($routeSegment !== $requestSegment) {
                return false;
            }
        }

        return $parameters;
    }

    private function abort(int $code): never
    {
        http_response_code($code);
        require view_path("errors/{$code}.php");
        exit;
    }
}