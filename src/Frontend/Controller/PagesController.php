<?php

namespace App\Frontend\Controller;

use App\Repository\PagesRepository;
use App\Repository\CategoriesRepository;
use App\Admin\Support\AuthService;

class PagesController extends AbstractController {

    public function __construct(
        private PagesRepository $pagesRepository,
        CategoriesRepository $categoriesRepository,
        AuthService $authService
    ) {
        parent::__construct($categoriesRepository, $authService);
    }

    public function showPage($pageKey) {
        $page = $this->pagesRepository->fetchBySlug($pageKey);

        if (empty($page)) {
            $this->error404();
            return;
        }

        $this->render('pages/showPage', [
            'page' => $page
        ]);
    }
}