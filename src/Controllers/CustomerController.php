<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Transaction;
use App\Models\User;

class CustomerController extends Controller {
    public function transactions() {
        $email = $_SESSION['user'];
        $transactions = Transaction::findByEmail($email);
        $this->view('customer/transactions', ['transactions' => $transactions]);
    }

    public function deposit() {
        $email = $_SESSION['user'];
        $amount = $_POST['amount'];

        $transaction = new Transaction($email, 'deposit', $amount);
        $transaction->save();

        $this->redirect('customer/transactions');
    }

    public function withdraw() {
        $email = $_SESSION['user'];
        $amount = $_POST['amount'];

        $transaction = new Transaction($email, 'withdraw', $amount);
        $transaction->save();

        $this->redirect('customer/transactions');
    }

    public function transfer() {
        $email = $_SESSION['user'];
        $recipientEmail = $_POST['recipient_email'];
        $amount = $_POST['amount'];

        $transaction = new Transaction($email, 'transfer', $amount);
        $transaction->save();

        $transaction = new Transaction($recipientEmail, 'deposit', $amount);
        $transaction->save();

        $this->redirect('customer/transactions');
    }

    public function balance() {
        $email = $_SESSION['user'];
        $transactions = Transaction::findByEmail($email);
        $balance = 0;

        foreach ($transactions as $transaction) {
            if ($transaction['type'] === 'deposit' || $transaction['type'] === 'transfer') {
                $balance += $transaction['amount'];
            } else if ($transaction['type'] === 'withdraw') {
                $balance -= $transaction['amount'];
            }
        }

        $this->view('customer/balance', ['balance' => $balance]);
    }
}
