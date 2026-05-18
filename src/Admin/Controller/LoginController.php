<?php

namespace App\Admin\Controller;

use App\Admin\Support\AuthService;

class LoginController extends AbstractAdminController {

    public function logout() {
        $this->authService->logoutAdmin();
        header('Location: index.php?' . http_build_query(['route' => 'admin/login']));
    }

    public function login() {
        if ($this->authService->isAdminLoggedIn()) {
            header('Location: index.php?' . http_build_query(['route' => 'admin/startups']));
            return;
        }
        
        // var_dump("LoginController::login() has been called!");
        $loginError = false;
        if (!empty($_POST)) {
            $username = @(string) ($_POST['username'] ?? '');
            $password = @(string) ($_POST['password'] ?? '');

            if (!empty($username) && !empty($password)) {
                $loginOk = $this->authService->handleAdminLogin($username, $password);
                if ($loginOk === true) {
                    header('Location: index.php?' . http_build_query(['route' => 'admin/startups']));
                    return;
                }
                else {
                    $loginError = true;
                }
            }
            else {
                $loginError = true;
            }
            
        }
        $csrfToken = function_exists('csrf_token') ? csrf_token() : '';

        ob_start();
        require __DIR__ . '/../../../views/admin/login/login.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/admin-login.view.php';
    }
}