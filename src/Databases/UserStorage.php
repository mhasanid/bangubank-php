<?php

namespace App\Databases;

use App\Models\User;

class UserStorage {
    // const USER_STORAGE_LOCATION = "../storage/users.json";

    private $storage;

    public function __construct(DatabaseInterface $storage) {
        $this->storage = $storage;
    }

    public function all(): array {
        return $this->storage->read();
    }

    public function findByEmail($email): ?User {
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
        if($this->isUserExist($user)){
            return false;
        }
        $users[] = $user->toArray();
        return $this->storage->write($users);
    }

    public function isUserExist(User $user):bool{
        $users = $this->all();

        foreach ($users as $userData) {
            if ($userData['email'] === $user->email) {
                return true;
            }
        }
        return false;
    }
}
