<?php

namespace App\Controllers;

use App\Models\Service;
use Core\App;
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
        $userId = Session::get('user')['id'];

        $serviceModel = new Service($this->database);

        view('dashboard.php', [
            'user' => Session::get('user'),
            'services' => $serviceModel->all(),
            'totalServicePrice' => $serviceModel->totalPriceForUser($userId),
            'pendingServices' => $serviceModel->latestPendingForUser($userId),
        ]);
    }

}
