<?php

namespace App\Models;

class User {
    const USER_STORAGE_LOCATION = "../storage/users.json";
    const ADMIN_USER = "admin";
    const CUSTOMER_USER = "customer";

    public $name;
    public $email;
    private $password;
    public $user_role;

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->user_role = self::CUSTOMER_USER;
    }

    public static function all() {
        $users = file_get_contents(self::USER_STORAGE_LOCATION);
        return json_decode($users, true) ?: [];
    }

    public static function findByEmail($email) {
        $users = self::all();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return new self($user['name'], $user['email'], $user['password'], $user['role']);
            }
        }
        return null;
    }

    public function save():bool {
        $users = self::all();

        foreach ($users as $user) {
            if ($user['email'] === $this->email) {
                return false;
            }
        }
        $users[] = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->user_role
        ];
        if(file_put_contents(self::USER_STORAGE_LOCATION, json_encode($users))){
            return true;
        };
        return false;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}
