<?php

namespace App\Models;

class User {
    const ADMIN_USER = "admin";
    const CUSTOMER_USER = "customer";
    
    public function __construct(
        public string $name,
        public string $email,
        private string $password,
        public string $role = self::CUSTOMER_USER
    ) {
        // $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    
    public function verifyPassword(string $password) {
        return password_verify($password, $this->password);
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role
        ];
    }
}
