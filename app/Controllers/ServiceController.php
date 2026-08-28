<?php

namespace App\Controllers;

use App\DTOs\CreateUserData;
use App\Models\Service;
use App\Models\User;
use Core\App;
use Core\Database;
use Core\Validator;
use PDOException;

class ServiceController
{
    private Database $database;

    public function __construct()
    {
        $this->database = App::resolve(Database::class);
    }

    public function finish($id): void
    {
        if (!Validator::number($id, 1)) {
            redirect('/');
        }

        $serviceModel = new Service($this->database);
        $serviceModel->find($id);
        $serviceModel->finish($id);

        redirect('/');
    }

    public function delete(int $id): void
    {
        if (!Validator::number($id, 1)) {
            redirect('/');
        }

        $serviceModel = new Service($this->database);
        $serviceModel->find($id);
        $serviceModel->delete($id);

        redirect('/');
    }
}