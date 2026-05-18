<?php

namespace App\Frontend\Controller;

use App\Admin\Support\AuthService;

class AuthController {
    public function __construct(private AuthService $authService) {}

    public function showRegister() {
        $error = null;
        $success = null;

        if (!empty($_POST)) {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

            if ($email === '' || $password === '' || $confirmPassword === '') {
                $error = 'Please fill in all required fields.';
            } else if ($password !== $confirmPassword) {
                $error = 'Passwords do not match.';
            } else {
                $registered = $this->authService->registerFounder($email, $password);
                if ($registered) {
                    header('Location: index.php?' . http_build_query(['route' => 'showcase']));
                    exit;
                }

                $error = 'Registration failed. Use a valid @uog.edu.et email and a new address.';
            }
        }

        ob_start();
        require __DIR__ . '/../../../views/auth/student-register.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }

    public function showLogin() {
        $error = null;
        $success = null;

        if (!empty($_POST)) {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $error = 'Please enter your institutional email and password.';
            } else {
                $loginOk = $this->authService->handleFounderLogin($email, $password);
                if ($loginOk) {
                    header('Location: index.php?' . http_build_query(['route' => 'showcase']));
                    exit;
                }

                $error = 'Invalid credentials or access restricted.';
            }
        }

        ob_start();
        require __DIR__ . '/../../../views/auth/student-login.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }
}
