<?php

namespace App\Controllers;

use App\Domain\CommissionCalculator;
use App\Models\Service;
use Core\App;
use Core\Database;
use Core\Validator;

class ServiceController
{
    private Database $database;
    private CommissionCalculator $calculator;

    public function __construct()
    {
        $this->database = App::resolve(Database::class);
        $this->calculator = new CommissionCalculator();
    }

    public function finish($id): void
    {
        if (!Validator::number($id, 1)) {
            redirect('/');
        }

        $serviceModel = new Service($this->database);
        $service = $serviceModel->find($id);
        $commission = $this->calculator->calculate($service['price']);

        $serviceModel->finish($id, $commission);

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