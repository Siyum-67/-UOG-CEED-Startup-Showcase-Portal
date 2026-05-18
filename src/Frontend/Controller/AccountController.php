<?php

namespace App\Frontend\Controller;

use App\Admin\Support\AuthService;
use App\Repository\CategoriesRepository;
use App\Repository\StartupsRepository;

class AccountController extends AbstractController {

    public function __construct(
        AuthService $authService,
        CategoriesRepository $categoriesRepository,
        private StartupsRepository $startupsRepository
    ) {
        parent::__construct($categoriesRepository, $authService);
    }

    public function register() {
        if ($this->authService->isFounderLoggedIn()) {
            header('Location: index.php?' . http_build_query(['route' => 'account/startups']));
            return;
        }

        $errors = [];
        if (!empty($_POST)) {
            $email = @(string) ($_POST['email'] ?? '');
            $password = @(string) ($_POST['password'] ?? '');
            $confirm = @(string) ($_POST['confirm'] ?? '');

            if (empty($email) || empty($password)) {
                $errors[] = 'Email and password are required.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please provide a valid email address.';
            }
            if (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters.';
            }
            if ($password !== $confirm) {
                $errors[] = 'Passwords do not match.';
            }

            if (empty($errors)) {
                $registered = $this->authService->registerFounder($email, $password);
                if ($registered) {
                    $this->authService->handleFounderLogin($email, $password);
                    header('Location: index.php?' . http_build_query(['route' => 'account/startups']));
                    return;
                }
                $errors[] = 'Registration failed. Check your email domain or try a different email.';
            }
        }

        $this->render('account/register', [
            'errors' => $errors
        ]);
    }

    public function login() {
        if ($this->authService->isFounderLoggedIn()) {
            header('Location: index.php?' . http_build_query(['route' => 'account/startups']));
            return;
        }

        $errors = [];
        if (!empty($_POST)) {
            $email = @(string) ($_POST['email'] ?? '');
            $password = @(string) ($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $errors[] = 'Email and password are required.';
            } else {
                $loginOk = $this->authService->handleFounderLogin($email, $password);
                if ($loginOk) {
                    header('Location: index.php?' . http_build_query(['route' => 'account/startups']));
                    return;
                }
                $errors[] = 'Invalid login or restricted account.';
            }
        }

        $this->render('account/login', [
            'errors' => $errors
        ]);
    }

    public function logout() {
        $this->authService->logoutFounder();
        header('Location: index.php?' . http_build_query(['route' => 'account/login']));
    }

    public function startups() {
        $this->authService->ensureFounderLoggedIn();
        $ownerId = $this->authService->getFounderUserId();
        $startups = $ownerId ? $this->startupsRepository->fetchByOwner($ownerId) : [];

        $this->render('account/startups', [
            'startups' => $startups
        ]);
    }
}
