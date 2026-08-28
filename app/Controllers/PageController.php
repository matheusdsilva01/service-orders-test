<?php

namespace App\Controllers;

use App\Models\Service;
use Core\App;
use Core\Container;
use Core\Database;
use Core\Session;

class PageController
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::resolve(Database::class);
    }

    public function home(): void
    {
        view('dashboard.php', [
            'user' => Session::get('user'),
            'services' => new Service($this->database)->all()
        ]);
    }

}
