<?php

namespace App\Databases;

use App\Models\User;

class UserStorage {

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

    public function findById($id): ?User {
        $users = $this->all();
        foreach ($users as $userData) {
            if ($userData['id'] == $id) {
                $user = new User($userData['name'], $userData['email'], $userData['password'], $userData['role']);
                $user->setId($userData['id']);
                return $user;
            }
        }
        return null;
    }

    public function findAllByRole($role): array {
        $users = $this->all();
        $usersByRole = [];
        
        foreach ($users as $userData) {
            if ($userData['role'] === $role) {
                $user = new User($userData['name'], $userData['email'], $userData['password'], $userData['role']);
                $user->setId($userData['id']);
                $usersByRole[] = $user;
            }
        }
        return $usersByRole;
    }
    

    public function save(User $user): bool {
        $users = $this->all();
        if($this->isUserExist($user)){
            return false;
        }
        $user->setId($this->setIdIncrement($users));
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

    private function setIdIncrement(array $users):int{
        if(empty($users)){
            return 1;
        }
        $lastId = (int)$users[count($users)-1]['id'];
        return $lastId+1;
        
    }
}
