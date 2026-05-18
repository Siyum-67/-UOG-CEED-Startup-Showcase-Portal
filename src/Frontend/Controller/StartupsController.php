<?php

namespace App\Frontend\Controller;

use App\Admin\Support\AuthService;
use App\Repository\StartupsRepository;
use App\Repository\CategoriesRepository;
use App\Repository\CommentsRepository;
use App\Repository\MediaRepository;
use App\Support\UploadService;

class StartupsController extends AbstractController {

    public function __construct(
        private StartupsRepository $startupsRepository,
        CategoriesRepository $categoriesRepository,
        private CommentsRepository $commentsRepository,
        private MediaRepository $mediaRepository,
        private UploadService $uploadService,
        AuthService $authService
    ) {
        parent::__construct($categoriesRepository, $authService);
    }

    public function index() {
        $categorySlug = @(string) ($_GET['category'] ?? '');
        $category = null;

        if (!empty($categorySlug)) {
            $category = $this->categoriesRepository->fetchBySlug($categorySlug);
            if (empty($category)) {
                $this->error404();
                return;
            }
            $startups = $this->startupsRepository->getByCategoryId($category->id);
        }
        else {
            $startups = $this->startupsRepository->getAll();
        }

        $this->render('startups/index', [
            'startups' => $startups,
            'category' => $category
        ]);
    }

    public function show() {
        $slug = @(string) ($_GET['slug'] ?? '');
        $startup = $this->startupsRepository->fetchBySlug($slug);
        if (empty($startup)) {
            $this->error404();
            return;
        }

        $commentErrors = $_SESSION['flash_comment_error'] ?? [];
        $commentOld = $_SESSION['flash_comment_old'] ?? [];
        $commentSuccess = $_SESSION['flash_comment_success'] ?? null;
        unset($_SESSION['flash_comment_error'], $_SESSION['flash_comment_old'], $_SESSION['flash_comment_success']);

        $prefillName = (string) ($_SESSION['founderName'] ?? '');
        if ($prefillName === '') {
            $email = (string) ($_SESSION['founderEmail'] ?? '');
            if ($email !== '') {
                $prefillName = explode('@', $email)[0];
            }
        }

        $comments = $this->commentsRepository->getByStartupId($startup->id);
        $media = $this->mediaRepository->getByStartupId($startup->id);
        $category = $this->categoriesRepository->fetchById($startup->category_id);

        $this->render('startups/show', [
            'startup' => $startup,
            'comments' => $comments,
            'media' => $media,
            'category' => $category,
            'commentErrors' => $commentErrors,
            'commentOld' => $commentOld,
            'commentSuccess' => $commentSuccess,
            'prefillName' => $prefillName
        ]);
    }

    public function create() {
        $this->authService->ensureFounderLoggedIn();
        $categories = $this->categoriesRepository->getAll();
        $errors = [];

        if (!empty($_POST)) {
            $data = $this->collectStartupData();
            $errors = $this->validateStartupData($data);

            $uploadErrors = $this->uploadService->validateUploads($_FILES['media'] ?? []);
            if (!empty($uploadErrors)) {
                $errors = array_merge($errors, $uploadErrors);
            }

            if (empty($errors)) {
                $data['owner_id'] = $this->authService->getFounderUserId();
                $data['slug'] = $this->slugify($data['name']);

                if ($this->startupsRepository->getSlugExists($data['slug'])) {
                    $errors[] = 'A startup with that name already exists.';
                }
                else {
                    $startupId = $this->startupsRepository->create($data);
                    $uploadResult = $this->uploadService->processUploads($_FILES['media'] ?? []);
                    foreach ($uploadResult['saved'] as $file) {
                        $this->mediaRepository->create($startupId, $file['type'], $file['path']);
                    }
                    header('Location: index.php?' . http_build_query(['route' => 'startups/show', 'slug' => $data['slug']]));
                    return;
                }
            }
        }

        $this->render('startups/create', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    }

    public function edit() {
        $this->authService->ensureFounderLoggedIn();
        $categories = $this->categoriesRepository->getAll();
        $errors = [];
        $id = @(int) ($_GET['id'] ?? 0);

        $startup = $this->startupsRepository->fetchById($id);
        if (empty($startup) || $startup->owner_id !== $this->authService->getFounderUserId()) {
            $this->error404();
            return;
        }

        if (!empty($_POST)) {
            $data = $this->collectStartupData();
            $errors = $this->validateStartupData($data);
            $hasNewMedia = $this->hasUploadedFiles($_FILES['media'] ?? []);

            if ($hasNewMedia) {
                $uploadErrors = $this->uploadService->validateUploads($_FILES['media'] ?? []);
                if (!empty($uploadErrors)) {
                    $errors = array_merge($errors, $uploadErrors);
                }
            }

            if (empty($errors)) {
                $this->startupsRepository->update($id, $data);
                if ($hasNewMedia) {
                    $uploadResult = $this->uploadService->processUploads($_FILES['media'] ?? []);
                    if (!empty($uploadResult['errors'])) {
                        $errors = array_merge($errors, $uploadResult['errors']);
                    }
                    else {
                        $existingMedia = $this->mediaRepository->getByStartupId($id);
                        foreach ($existingMedia as $item) {
                            $this->removeMediaFile($item->path ?? '');
                        }
                        $this->mediaRepository->deleteByStartupId($id);

                        foreach ($uploadResult['saved'] as $file) {
                            $this->mediaRepository->create($id, $file['type'], $file['path']);
                        }
                    }
                }
                if (empty($errors)) {
                    header('Location: index.php?' . http_build_query(['route' => 'startups/show', 'slug' => $startup->slug]));
                    return;
                }
            }
        }

        $media = $this->mediaRepository->getByStartupId($id);

        $this->render('startups/edit', [
            'startup' => $startup,
            'categories' => $categories,
            'errors' => $errors,
            'media' => $media
        ]);
    }

    private function collectStartupData(): array {
        return [
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'name' => trim((string) ($_POST['name'] ?? '')),
            'short_pitch' => trim((string) ($_POST['short_pitch'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'team' => trim((string) ($_POST['team'] ?? '')),
            'stage' => trim((string) ($_POST['stage'] ?? '')),
            'drive_link' => trim((string) ($_POST['drive_link'] ?? '')) ?: null
        ];
    }

    private function validateStartupData(array $data): array {
        $errors = [];
        if (empty($data['category_id'])) {
            $errors[] = 'Please choose a category.';
        }
        if (empty($data['name']) || empty($data['short_pitch']) || empty($data['description']) || empty($data['team']) || empty($data['stage'])) {
            $errors[] = 'Please fill in all required fields.';
        }
        return $errors;
    }

    private function slugify(string $name): string {
        $slug = strtolower($name);
        $slug = str_replace(['/', ' ', '.'], ['-', '-', '-'], $slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        return trim($slug, '-');
    }

    private function hasUploadedFiles(array $files): bool {
        if (empty($files['name']) || !is_array($files['name'])) {
            return false;
        }

        foreach ($files['name'] as $name) {
            if (!empty($name)) {
                return true;
            }
        }

        return false;
    }

    private function removeMediaFile(string $path): void {
        if ($path === '') {
            return;
        }

        $absolutePath = __DIR__ . '/../../../' . ltrim($path, '/\\');
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }
    }
}
