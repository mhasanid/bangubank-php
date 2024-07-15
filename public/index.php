<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\AdminController;

$router = new Router();

// Auth routes
$router->add('', 'GET', [AuthController::class, 'showHomepage']);
$router->add('login', 'GET', [AuthController::class, 'showLogin']);
$router->add('login', 'POST', [AuthController::class, 'login']);
$router->add('register', 'GET', [AuthController::class, 'showRegister']);
$router->add('register', 'POST', [AuthController::class, 'register']);
$router->add('logout', 'GET', [AuthController::class, 'logout']);

// Customer routes
$router->add('customer/transactions', 'GET', [CustomerController::class, 'transactions']);
$router->add('customer/deposit', 'GET', [CustomerController::class, 'showDeposit']);
$router->add('customer/deposit', 'POST', [CustomerController::class, 'deposit']);
$router->add('customer/withdraw', 'GET', [CustomerController::class, 'showWithdraw']);
$router->add('customer/withdraw', 'POST', [CustomerController::class, 'withdraw']);
$router->add('customer/transfer', 'GET', [CustomerController::class, 'showTransfer']);
$router->add('customer/transfer', 'POST', [CustomerController::class, 'transfer']);
// $router->add('customer/balance', 'GET', [CustomerController::class, 'balance']);

// Admin routes
$router->add('admin/transactions', 'GET', [AdminController::class, 'transactions']);
$router->add('admin/search-transactions', 'POST', [AdminController::class, 'searchTransactions']);
$router->add('admin/customers', 'GET', [AdminController::class, 'customers']);

$router->dispatch();
