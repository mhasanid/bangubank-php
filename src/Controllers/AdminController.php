<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Databases\JsonFileProcessor;
use App\Databases\TransactionStorage;
use App\Databases\UserStorage;

class AdminController extends Controller {
    private UserStorage $userAgent;
    private TransactionStorage $transactionHelper;

    public function __construct()
    {
        $this->userAgent = new UserStorage(new JsonFileProcessor(JsonFileProcessor::USER_FILE_PATH));
        $this->transactionHelper = new TransactionStorage(new JsonFileProcessor(JsonFileProcessor::TRANSACTION_FILE_PATH));
    }

    public function transactions() {
        $transactions = $this->transactionHelper->all();
        $this->view('admin/transactions', ['transactions' => $transactions]);
    }

    public function searchTransactions() {
        $email = $_POST['email'];
        $transactions = $this->transactionHelper->findByEmail($email);
        $this->view('admin/transactions', ['transactions' => $transactions]);
    }

    public function customers() {
        $user = $this->userAgent->findByEmail($_SESSION['user']);
        $customers = $this->userAgent->findAllByRole('customer');
        $this->view('admin/customers', ['loggedinUser'=>$user,'customers' => $customers]);
    }
}
