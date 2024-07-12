<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Transaction;
use App\Models\User;

class AdminController extends Controller {
    public function transactions() {
        $transactions = Transaction::all();
        $this->view('admin/transactions', ['transactions' => $transactions]);
    }

    public function searchTransactions() {
        $email = $_POST['email'];
        $transactions = Transaction::findByEmail($email);
        $this->view('admin/transactions', ['transactions' => $transactions]);
    }

    public function customers() {
        $customers = User::all();
        $this->view('admin/customers', ['customers' => $customers]);
    }
}
