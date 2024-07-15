<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Databases\BalanceStorage;
use App\Databases\JsonFileProcessor;
use App\Databases\TransactionStorage;
use App\Models\Transaction;
use App\Models\User;

class CustomerController extends Controller {
    
    private TransactionStorage $transactionHelper;
    private BalanceStorage $balanceHelper;

    public function __construct()
    {
        $this->transactionHelper = new TransactionStorage(new JsonFileProcessor(JsonFileProcessor::TRANSACTION_FILE_PATH));
        $this->balanceHelper = new BalanceStorage(new JsonFileProcessor(JsonFileProcessor::BALANCE_FILE_PATH));
    }

    public function transactions() {
        $email = $_SESSION['user'];
        $transactions = $this->transactionHelper->findByEmail($email);
        $balance = $this->balanceHelper->getBalanceByEmail($email);
        $this->view('customer/transactions', ['transactions' => $transactions, 'balance'=>$balance]);
    }

    public function showDeposit(){
        $email = $_SESSION['user'];
        $balance = $this->balanceHelper->getBalanceByEmail($email);
        if($email){
            $this->view('customer/deposit', ['balance'=>$balance]);
        }else{
            $this->redirect('login');
        }
    }

    public function deposit() {
        $email = $_SESSION['user'];
        $amount = $_POST['amount'];

        $transaction = new Transaction($email, $email, 'deposit', $amount);
        $this->transactionHelper->save($transaction);
        $this->balanceHelper->depositBalance($email, $amount);
        
        $this->redirect('customer/transactions');
 
    }


    public function showWithdraw(){
        $email = $_SESSION['user'];
        $balance = $this->balanceHelper->getBalanceByEmail($email);
        if($email){
            $this->view('customer/withdraw', ['balance'=>$balance]);
        }else{
            $this->redirect('login');
        }
    }

    // TODO: balance check for withdraw: cannot be withdrawn if the balance is zero 

    public function withdraw() {
        $email = $_SESSION['user'];
        $amount = $_POST['amount'];

        $transaction = new Transaction($email, $email, 'withdraw', $amount);
        $this->transactionHelper->save($transaction);
        $this->balanceHelper->withdrawBalance($email, $amount);
        
        $this->redirect('customer/transactions');

    }


    public function showTransfer(){
        $email = $_SESSION['user'];
        $balance = $this->balanceHelper->getBalanceByEmail($email);
        if($email){
            $this->view('customer/transfer', ['balance'=>$balance]);
        }else{
            $this->redirect('login');
        }
    }

    // TODO: Input validation: 
    // 1. if the user enter his own email into transfer email
    // 2. if the other user doesnot exist.

    // TODO: balance check for transfer: cannot be transferred if the balance is zero 
    // 

    public function transfer() {
        $email = $_SESSION['user'];
        $recipientEmail = $_POST['email'];
        $amount = $_POST['amount'];

        $transaction = new Transaction($email, $recipientEmail, 'withdraw', $amount);
        $this->transactionHelper->save($transaction);
        $this->balanceHelper->withdrawBalance($email, $amount);

        $transaction = new Transaction($recipientEmail, $email, 'deposit', $amount);
        $this->transactionHelper->save($transaction);
        $this->balanceHelper->depositBalance($recipientEmail, $amount);

        $this->redirect('customer/transactions');

    }

    // public function balance() {
    //     $email = $_SESSION['user'];
    //     $transactions = $this->transactionHelper->findByEmail($email);
    //     $balance = 0;

    //     foreach ($transactions as $transaction) {
    //         if ($transaction['type'] === 'deposit' || $transaction['type'] === 'transfer') {
    //             $balance += $transaction['amount'];
    //         } else if ($transaction['type'] === 'withdraw') {
    //             $balance -= $transaction['amount'];
    //         }
    //     }

    //     $this->view('customer/balance', ['balance' => $balance]);
    // }
}
