<?php

namespace Core\Middleware;

class Authenticated
{
    public function handle(): void
    {
        if (!($_SESSION['user'] ?? false)) {
            redirect('/');
        }
    }
}
