<?php

namespace App\Controllers;

use App\Domain\CommissionCalculator;
use App\DTOs\CreateServiceData;
use App\DTOs\UpdateServiceData;
use App\Models\Service;
use Core\App;
use Core\Database;
use Core\Session;
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

    public function create(): void
    {
        view('services/create.php');
    }

    public function store(): void
    {
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';

        $errors = [];

        if (!Validator::string($description, 1, 45)) {
            $errors['description'] = 'A descrição deve ter entre 1 e 45 caracteres.';
        }

        if (!Validator::number($price, 0.001, 99_999_999.999)) {
            $errors['price'] = 'O valor deve ser um número entre 0.001 e 99999999.999.';
        }

        if (!empty($errors)) {
            view('services/create.php', [
                'errors' => $errors,
                'old' => [
                    'description' => $description,
                    'price' => $price,
                ],
            ]);
            return;
        }

        $serviceModel = new Service($this->database);
        $authUserId = Session::get('user')['id'];

        $data = new CreateServiceData(
            $description,
            $price,
            $authUserId
        );
        $serviceModel->create($data);

        redirect('/');
    }

    public function edit(mixed $id): void
    {
        if (!Validator::number($id, 1)) {
            view('errors/404.php');
            return;
        }

        $serviceModel = new Service($this->database);
        $service = $serviceModel->find($id);

        if ($service['finished_at'] !== null) {
            redirect('/');
        }

        view('services/edit.php', [
            'service' => $service,
            'errors' => [],
        ]);
    }

    public function update($id): void
    {
        if (!Validator::number($id, 1)) {
            view('errors/404.php');
            return;
        }

        $serviceModel = new Service($this->database);
        $service = $serviceModel->find($id);

        if ($service['finished_at'] !== null) {
            redirect('/');
        }

        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';

        $errors = [];

        if (!Validator::string($description, 1, 45)) {
            $errors['description'] = 'A descrição deve ter entre 1 e 45 caracteres.';
        }

        if (!Validator::number($price, 0.001, 99_999_999.999)) {
            $errors['price'] = 'O valor deve ser um número entre 0.001 e 99999999.999.';
        }

        if (!empty($errors)) {
            view('services/edit.php', [
                'service' => $service,
                'errors' => $errors,
                'old' => [
                    'description' => $description,
                    'price' => $price,
                ],
            ]);
            return;
        }
        $data = new UpdateServiceData(
            $description,
            (float)$price
        );
        $serviceModel->update($id, $data);

        redirect('/');
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