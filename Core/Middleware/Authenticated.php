<?php

namespace Core\Middleware;

use Core\Session;

class Authenticated
{
    public function handle(): void
    {
        if (!Session::get('user')) {
            redirect('/login');
        }
    }
}
