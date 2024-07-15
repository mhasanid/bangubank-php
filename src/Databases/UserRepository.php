<?php

namespace App\Databases;

use App\Models\User;

class UserRepository {
    const USER_STORAGE_LOCATION = "../storage/users.json";

    public function all(): array {
        $users = file_get_contents(self::USER_STORAGE_LOCATION);
        return json_decode($users, true) ?: [];
    }

    public function findByEmail($email): User {
        $users = $this->all();
        foreach ($users as $userData) {
            if ($userData['email'] === $email) {
                return new User($userData['name'], $userData['email'], $userData['password'], $userData['role']);
            }
        }
        return null;
    }

    public function findAllByRole($role): array {
        $users = $this->all();
        $usersByRole = [];
        
        foreach ($users as $userData) {
            if ($userData['role'] === $role) {
                $usersByRole[] = new User($userData['name'], $userData['email'], $userData['password'], $userData['role']);
            }
        }
        
        return $usersByRole;
    }
    

    public function save(User $user): bool {
        $users = $this->all();

        foreach ($users as $userData) {
            if ($userData['email'] === $user->email) {
                return false;
            }
        }

        $users[] = $user->toArray();
        if (file_put_contents(self::USER_STORAGE_LOCATION, json_encode($users))) {
            return true;
        }

        return false;
    }
}
