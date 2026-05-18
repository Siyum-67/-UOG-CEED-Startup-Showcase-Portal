<?php

namespace App\Admin\Controller;

use App\Admin\Support\AuthService;
use App\Repository\UsersRepository;

class UsersAdminController extends AbstractAdminController {

    public function __construct(
        AuthService $authService,
        private UsersRepository $usersRepository
    ) {
        parent::__construct($authService);
    }

    public function index() {
        $users = $this->usersRepository->getAll();
        $this->render('users/index', [
            'users' => $users
        ]);
    }

    public function restrict() {
        $id = @(int) ($_POST['id'] ?? 0);
        $restricted = @(int) ($_POST['restricted'] ?? 0) === 1;
        if (!empty($id)) {
            $this->usersRepository->setRestricted($id, $restricted);
        }
        header('Location: index.php?' . http_build_query(['route' => 'admin/users']));
    }

    public function promote() {
        $id = @(int) ($_POST['id'] ?? 0);
        $adminId = $this->authService->getAdminUserId();
        if (!empty($id) && $id !== $adminId) {
            $this->usersRepository->setRole($id, 'admin');
        }
        header('Location: index.php?' . http_build_query(['route' => 'admin/users']));
    }
}
