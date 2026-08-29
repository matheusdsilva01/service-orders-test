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
use PDOException;

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
            Session::flash('message', [
                'type' => 'error',
                'text' => 'Não foi possível cadastrar o serviço.',
            ]);

            redirect('/');
        }

        $serviceModel = new Service($this->database);
        $authUserId = Session::get('user')['id'];

        $data = new CreateServiceData(
            trim($description),
            $price,
            $authUserId
        );
        try {
            $serviceModel->create($data);
        } catch (PDOException) {
            Session::flash('message', [
                'type' => 'error',
                'text' => 'Não foi possível cadastrar o serviço.',
            ]);

            redirect('/');
        }

        Session::flash('message', [
            'type' => 'success',
            'text' => 'Serviço cadastrado com sucesso.',
        ]);

        redirect('/');
    }

    public function edit(string $id): void
    {
        if (!Validator::positiveInteger($id, 1)) {
            abort();
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

    public function update(string $id): void
    {
        if (!Validator::positiveInteger($id, 1)) {
            abort();
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
            trim($description),
            $price
        );
        $serviceModel->update($id, $data);

        redirect('/');
    }

    public function finish(string $id): void
    {
        if (!Validator::positiveInteger($id, 1)) {
            abort();
        }

        $serviceModel = new Service($this->database);
        $service = $serviceModel->find($id);
        $commission = $this->calculator->calculate($service['price']);

        $serviceModel->finish($id, $commission);

        redirect('/');
    }

    public function delete(string $id): void
    {
        if (!Validator::positiveInteger($id, 1)) {
            abort();
        }

        $serviceModel = new Service($this->database);
        $serviceModel->find($id);
        $serviceModel->delete($id);

        redirect('/');
    }
}
