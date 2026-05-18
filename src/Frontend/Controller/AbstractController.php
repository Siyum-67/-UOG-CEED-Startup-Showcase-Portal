<?php

namespace App\Frontend\Controller;

use App\Repository\CategoriesRepository;
use App\Admin\Support\AuthService;

abstract class AbstractController {
    public function __construct(
        protected CategoriesRepository $categoriesRepository,
        protected AuthService $authService
    ) {}

    protected function render($view, $params) {
        extract($params);
    
        ob_start();
        require __DIR__ . '/../../../views/frontend/' . $view . '.view.php';
        $contents = ob_get_clean();
        
        $categories = $this->categoriesRepository->getAll();
        $isFounderLoggedIn = $this->authService->isFounderLoggedIn();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }

    protected function error404() {
        http_response_code(404);
        
        $this->render('abstract/error404', []);
    }
}