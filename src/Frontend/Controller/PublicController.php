<?php

namespace App\Frontend\Controller;

use App\Repository\StartupsRepository;
use App\Repository\CategoriesRepository;
use App\Repository\MediaRepository;
use App\Repository\UsersRepository;

class PublicController {
    public function __construct(
        private StartupsRepository $startupsRepository,
        private CategoriesRepository $categoriesRepository,
        private MediaRepository $mediaRepository,
        private UsersRepository $usersRepository
    ) {}

    public function showcase() {
        $categories = $this->categoriesRepository->getAll();
        $categorySlug = (string) ($_GET['category'] ?? '');
        $activeCategory = null;
        foreach ($categories as $category) {
            if ($category->slug === $categorySlug) {
                $activeCategory = $category;
                break;
            }
        }

        if (!empty($activeCategory)) {
            $startups = $this->startupsRepository->getByCategoryId($activeCategory->id);
        }
        else {
            $startups = $this->startupsRepository->getAll();
        }
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->id] = $category->name;
        }

        $coverMap = [];
        foreach ($startups as $startup) {
            $media = $this->mediaRepository->getByStartupId($startup->id);
            foreach ($media as $item) {
                if ($item->type === 'image') {
                    $coverMap[$startup->id] = $item->path;
                    break;
                }
            }
        }

        ob_start();
        require __DIR__ . '/../../../views/public/showcase.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }

    public function founderShow() {
        $founderId = (int) ($_GET['id'] ?? 0);
        $founder = $founderId > 0 ? $this->usersRepository->findById($founderId) : null;
        if (empty($founder)) {
            http_response_code(404);
        }

        $startups = !empty($founder) ? $this->startupsRepository->fetchByOwner($founder->id) : [];
        $categories = $this->categoriesRepository->getAll();
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->id] = $category->name;
        }

        $coverMap = [];
        foreach ($startups as $startup) {
            $media = $this->mediaRepository->getByStartupId($startup->id);
            foreach ($media as $item) {
                if ($item->type === 'image') {
                    $coverMap[$startup->id] = $item->path;
                    break;
                }
            }
        }

        ob_start();
        require __DIR__ . '/../../../views/public/founder.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }
}
