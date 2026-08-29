<?php

namespace App\Controllers;

use App\Models\Service;
use Core\App;
use Core\Database;
use Core\Session;
use Core\Validator;

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

        $startDate = trim($_GET['start_date'] ?? '');
        $endDate = trim($_GET['end_date'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $serviceName = trim($_GET['service_name'] ?? '');
        $userName = trim($_GET['user_name'] ?? '');
        $filterErrors = [];
        $filters = compact('startDate', 'endDate', 'status', 'serviceName', 'userName');

        if ($startDate !== '' && !Validator::date($startDate)) {
            $filterErrors['start_date'] = 'A data inicial é inválida.';
            $filters['startDate'] = '';
        }

        if ($endDate !== '' && !Validator::date($endDate)) {
            $filterErrors['end_date'] = 'A data final é inválida.';
            $filters['endDate'] = '';
        }

        if (
            $startDate !== ''
            && $endDate !== ''
            && Validator::date($startDate)
            && Validator::date($endDate)
            && $startDate > $endDate
        ) {
            $filterErrors['date'] = 'A data inicial não pode ser maior que a data final.';

            $filters['startDate'] = '';
            $filters['endDate'] = '';
        }

        $validStatus = ['pending', 'finished'];

        if (!in_array($status, $validStatus) && $status !== '') {
            $filterErrors['status'] = 'Status inválido.';
            $filters['status'] = '';
        }

        if (!Validator::string($serviceName, 0, 45)) {
            $filterErrors['service_name'] = 'Nome do serviço inválido.';
            $filters['serviceName'] = '';
        }

        if (!Validator::string($userName, 0, 150)) {
            $filterErrors['user_name'] = 'Nome do usuário inválido.';
            $filters['userName'] = '';
        }

        $filtersValues = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'service_name' => $serviceName,
            'user_name' => $userName,
        ];

        view('dashboard.php', [
            'user' => Session::get('user'),
            'filterErrors' => $filterErrors,
            'services' => $serviceModel->all($filters),
            'totalServicePrice' => $serviceModel->totalPriceForUser($userId),
            'filterValues' => $filtersValues,
            'pendingServices' => $serviceModel->latestPendingForUser($userId),
        ]);
    }

}
