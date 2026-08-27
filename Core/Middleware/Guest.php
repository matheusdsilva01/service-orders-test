<?php

namespace Core\Middleware;

use Core\Session;

class Guest
{
    public function handle(): void
    {
        if (Session::get('user')) {
            redirect('/');
        }
    }
}
