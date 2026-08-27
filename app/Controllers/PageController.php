<?php

namespace App\Controllers;

use Core\Session;

class PageController
{
    public function home(): void
    {
        view('dashboard.php', [
            'user' => Session::get('user'),
        ]);
    }

}
