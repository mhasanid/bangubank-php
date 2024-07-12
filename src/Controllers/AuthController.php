<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    public function showHomepage() {
        if(!$this->showLoggedinUserDashboard()){
            $this->view('landing/homepage');
        }
    }

    public function showLogin() {
        if(!$this->showLoggedinUserDashboard()){
            $this->view('auth/login');
        }
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::findByEmail($email);

        if ($user && $user->verifyPassword($password)) {
            $_SESSION['user'] = $user->email;
            $this->redirect('customer/transactions');
        } else {
            $_SESSION['user-error'] = "Error login: Please try with correct credentials. If you are not registered please register.";
            $this->redirect('login');
        }
    }

    public function showRegister() {
        $this->view('auth/register');
    }

    public function register() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = new User($name, $email, $password);
        if($user->save()){
            $_SESSION['user'] = $user->email;
            $this->redirect('customer/transactions');
        }else{
            $_SESSION['user-exist'] = "User Exist: Please consider loging in.";
            $this->redirect('login');
        }

    }

    public function logout() {
        session_destroy();
        $this->redirect('login');
    }

    private function showLoggedinUserDashboard():bool{
        $user_email = $_SESSION['user'] ?: null;
        if($user_email){
            $user = User::findByEmail($user_email);
            if($user->user_role===User::CUSTOMER_USER){
                $this->redirect('customer/transactions');
            }elseif ($user->user_role===User::ADMIN_USER){
                $this->redirect('admin/transactions');
            }
            return true;
        }
        return false;
    }
}
