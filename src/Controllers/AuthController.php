<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Databases\BalanceStorage;
use App\Databases\JsonFileProcessor;
use App\Models\User;
use App\Databases\UserStorage;
use App\Models\Balance;
use App\Utils\Utility;

class AuthController extends Controller {
    private UserStorage $userRepo;
    private BalanceStorage $balanceHelper;

    public function __construct()
    {
        $this->userRepo = new UserStorage(new JsonFileProcessor(JsonFileProcessor::USER_FILE_PATH));
        $this->balanceHelper = new BalanceStorage(new JsonFileProcessor(JsonFileProcessor::BALANCE_FILE_PATH));
    }

    public function showHomepage() {
        if(!$this->showLoggedinUserDashboard()){
            $this->view('landing/homepage');
        }
    }

    public function showLogin() {
        $error = Utility::flash('error');
        if(!$this->showLoggedinUserDashboard()){
            $this->view('auth/login',['error'=>$error]);
        }
    }

    public function login() {
        $email = $_POST['email'];
        $password = Utility::sanitize($_POST['password']);

        $user = $this->userRepo->findByEmail($email);
        
        if (!$user) {
            Utility::flash('error','You are not registered. Please register.');
            $this->redirect('login');
        }
        if(!$user->verifyPassword($password)){
            Utility::flash('error','Please try with correct credentials.');
            $this->redirect('login');
        }
        $_SESSION['user'] = $user->email;              
        $this->showLoggedinUserDashboard();
    }

    public function showRegister() {
        $this->view('auth/register');
    }

    public function register() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash(Utility::sanitize($_POST['password']), PASSWORD_DEFAULT);;

        $user = new User($name, $email, $password);
        if($this->userRepo->isUserExist($user)){
            Utility::flash('error','You are already registered. Please login.');
            $this->redirect('login');
        }
        if(!$this->userRepo->save($user)){
            Utility::flash('error','Error occured! User cannot be registered.');
            $this->redirect('login');
        }
        $_SESSION['user'] = $user->email;
        $this->showLoggedinUserDashboard();
    }

    public function logout() {
        session_destroy();
        $this->redirect('login');

    }

    private function showLoggedinUserDashboard():bool{
        $user_email = $_SESSION['user'] ?: null;
        if(!$user_email){
            return false;
        }
        $user = $this->userRepo->findByEmail($user_email);
            if($user->role===User::CUSTOMER_USER){
                $userBalance = $this->balanceHelper->getBalanceByEmail($user->email);
                if(!$userBalance){
                    $this->balanceHelper->save(new Balance($user->email));
                }
                $this->redirect('customer/transactions');
            }elseif ($user->role===User::ADMIN_USER){
                $this->redirect('admin/customers');
            }
            return true;
    }
}
