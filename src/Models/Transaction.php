<?php

namespace App\Models;

class Transaction {
    public $userEmail;
    public $othersEmail;
    public $type;
    public $amount;

    public function __construct($userEmail, $othersEmail, $type, $amount) {
        $this->userEmail = $userEmail;
        $this->othersEmail = $othersEmail;
        $this->type = $type;
        $this->amount = $amount;
    }
}

