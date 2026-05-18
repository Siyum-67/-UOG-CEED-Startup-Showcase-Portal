<?php

namespace App\Admin\Controller;

use App\Admin\Support\AuthService;
use App\Repository\CommentsRepository;

class CommentsAdminController extends AbstractAdminController {

    public function __construct(
        AuthService $authService,
        private CommentsRepository $commentsRepository
    ) {
        parent::__construct($authService);
    }

    public function index() {
        $comments = $this->commentsRepository->getAllWithStartup();
        $this->render('comments/index', [
            'comments' => $comments
        ]);
    }

    public function delete() {
        $id = @(int) ($_POST['id'] ?? 0);
        if (!empty($id)) {
            $this->commentsRepository->delete($id);
        }
        header('Location: index.php?' . http_build_query(['route' => 'admin/comments']));
    }
}
