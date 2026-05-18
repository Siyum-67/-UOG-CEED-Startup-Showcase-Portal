<?php

namespace App\Admin\Controller;

use App\Admin\Support\AuthService;
use App\Repository\StartupsRepository;
use App\Repository\MediaRepository;

class StartupsAdminController extends AbstractAdminController {

    public function __construct(
        AuthService $authService,
        private StartupsRepository $startupsRepository,
        private MediaRepository $mediaRepository
    ) {
        parent::__construct($authService);
    }

    public function index() {
        $startups = $this->startupsRepository->getAllWithOwnerAndCategory();
        $this->render('startups/index', [
            'startups' => $startups
        ]);
    }

    public function delete() {
        $id = @(int) ($_POST['id'] ?? 0);
        if (!empty($id)) {
            $this->mediaRepository->deleteByStartupId($id);
            $this->startupsRepository->delete($id);
        }
        header('Location: index.php?' . http_build_query(['route' => 'admin/startups']));
    }
}
