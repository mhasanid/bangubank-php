<?php

namespace App\Models;

class Transaction {
    public $email;
    public $type;
    public $amount;

    public function __construct($email, $type, $amount) {
        $this->email = $email;
        $this->type = $type;
        $this->amount = $amount;
    }

    public static function all() {
        $transactions = file_get_contents(__DIR__ . '/../../storage/transactions.json');
        return json_decode($transactions, true) ?: [];
    }

    public function save() {
        $transactions = self::all();
        $transactions[] = [
            'email' => $this->email,
            'type' => $this->type,
            'amount' => $this->amount
        ];
        file_put_contents(__DIR__ . '/../../storage/transactions.json', json_encode($transactions));
    }

    public static function findByEmail($email) {
        $transactions = self::all();
        return array_filter($transactions, function ($transaction) use ($email) {
            return $transaction['email'] === $email;
        });
    }
}
