<?php

namespace App\Frontend\Controller;

use App\Admin\Support\AuthService;
use App\Repository\StartupsRepository;
use App\Repository\CategoriesRepository;
use App\Repository\UsersRepository;
use App\Repository\MediaRepository;
use App\Support\UploadService;

class StudentController {
    public function __construct(
        private AuthService $authService,
        private StartupsRepository $startupsRepository,
        private CategoriesRepository $categoriesRepository,
        private UsersRepository $usersRepository,
        private MediaRepository $mediaRepository,
        private UploadService $uploadService
    ) {}

    public function create() {
        $student = $this->requireStudent();
        if (empty($student)) {
            return;
        }

        $csrfToken = function_exists('csrf_token') ? csrf_token() : '';
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->logStartupSubmission('POST', $student->id);

            $data = [
                'category_id' => (int) ($_POST['category_id'] ?? 0),
                'name' => trim((string) ($_POST['name'] ?? '')),
                'short_pitch' => trim((string) ($_POST['short_pitch'] ?? '')),
                'description' => trim((string) ($_POST['description'] ?? '')),
                'team' => trim((string) ($_POST['team'] ?? '')),
                'stage' => trim((string) ($_POST['stage'] ?? '')),
                'drive_link' => trim((string) ($_POST['drive_link'] ?? '')) ?: null
            ];

            if (empty($data['category_id'])) {
                $errors[] = 'Please choose a category.';
            }
            if ($data['name'] === '' || $data['short_pitch'] === '' || $data['description'] === '' || $data['team'] === '' || $data['stage'] === '') {
                $errors[] = 'Please fill in all required fields.';
            }

            $uploadErrors = $this->uploadService->validateUploads($_FILES['media'] ?? []);
            if (!empty($uploadErrors)) {
                $errors = array_merge($errors, $uploadErrors);
            }

            if (empty($errors)) {
                $data['owner_id'] = $student->id;
                $data['slug'] = $this->slugify($data['name']);

                if ($this->startupsRepository->getSlugExists($data['slug'])) {
                    $errors[] = 'A startup with that name already exists.';
                }
                else {
                    $errorInfo = null;
                    $startupId = $this->startupsRepository->create($data, $errorInfo);
                    if ($startupId <= 0) {
                        $errors[] = 'Failed to save startup. Please try again.';
                        if (!empty($errorInfo)) {
                            $this->logStartupSubmission('DB_ERROR', $student->id, ['errorInfo' => $errorInfo]);
                        }
                    }
                    else {
                        $uploadResult = $this->uploadService->processUploads($_FILES['media'] ?? []);
                        foreach ($uploadResult['saved'] as $file) {
                            $this->mediaRepository->create($startupId, $file['type'], $file['path']);
                        }
                        $_SESSION['flash_success'] = 'Startup submitted successfully.';
                        header('Location: index.php?' . http_build_query(['route' => 'student/dashboard']));
                        exit;
                    }
                }
            }
        }

        ob_start();
        require __DIR__ . '/../../../views/student/submit-startup.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }

    public function dashboard() {
        $student = $this->requireStudent();
        if (empty($student)) {
            return;
        }

        $success = null;
        if (!empty($_SESSION['flash_success'])) {
            $success = (string) $_SESSION['flash_success'];
            unset($_SESSION['flash_success']);
        }

        $error = null;
        if (!empty($_SESSION['flash_error'])) {
            $error = (string) $_SESSION['flash_error'];
            unset($_SESSION['flash_error']);
        }

        $startups = $this->startupsRepository->fetchByOwner($student->id);
        $categories = $this->categoriesRepository->getAll();
        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category->id] = $category->name;
        }

        ob_start();
        require __DIR__ . '/../../../views/student/dashboard.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }

    public function deleteStartup() {
        $student = $this->requireStudent();
        if (empty($student)) {
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Invalid startup selection.';
            header('Location: index.php?' . http_build_query(['route' => 'student/dashboard']));
            exit;
        }

        $startup = $this->startupsRepository->fetchById($id);
        if (empty($startup) || (int) $startup->owner_id !== (int) $student->id) {
            $_SESSION['flash_error'] = 'You do not have permission to delete this startup.';
            header('Location: index.php?' . http_build_query(['route' => 'student/dashboard']));
            exit;
        }

        $media = $this->mediaRepository->getByStartupId($id);
        foreach ($media as $item) {
            $this->removeMediaFile($item->path ?? '');
        }

        $this->mediaRepository->deleteByStartupId($id);
        $this->startupsRepository->delete($id);

        $_SESSION['flash_success'] = 'Startup deleted successfully.';
        header('Location: index.php?' . http_build_query(['route' => 'student/dashboard']));
        exit;
    }

    public function profile() {
        $student = $this->requireStudent();
        if (empty($student)) {
            return;
        }

        $errors = [];
        $success = null;
        $formData = $this->buildProfileFormData($student, []);

        $this->renderProfile($student, $formData, $errors, $success);
    }

    public function updateProfile() {
        $student = $this->requireStudent();
        if (empty($student)) {
            return;
        }

        $errors = [];
        $success = null;

        $input = [
            'full_name' => trim((string) ($_POST['full_name'] ?? '')),
            'year_of_study' => (string) ($_POST['year_of_study'] ?? ''),
            'department' => trim((string) ($_POST['department'] ?? '')),
            'bio' => trim((string) ($_POST['bio'] ?? '')),
        ];

        if ($input['full_name'] !== '' && strlen($input['full_name']) > 120) {
            $errors[] = 'Full name must be 120 characters or fewer.';
        }

        if ($input['department'] !== '' && strlen($input['department']) > 120) {
            $errors[] = 'Department must be 120 characters or fewer.';
        }

        if ($input['bio'] !== '' && strlen($input['bio']) > 500) {
            $errors[] = 'Bio must be 500 characters or fewer.';
        }

        $yearValue = null;
        if ($input['year_of_study'] !== '') {
            $yearValue = (int) $input['year_of_study'];
            if (!in_array($yearValue, [1, 2, 3, 4, 5], true)) {
                $errors[] = 'Year of study must be between 1 and 5.';
            }
        }

        $avatarPath = $student->avatar_path;
        $uploadedAvatar = $this->handleAvatarUpload($_FILES['avatar'] ?? null, $errors);
        if (!empty($uploadedAvatar)) {
            $avatarPath = $uploadedAvatar;
        }

        if (empty($errors)) {
            $data = [
                'full_name' => $input['full_name'] !== '' ? $input['full_name'] : null,
                'year_of_study' => $yearValue,
                'department' => $input['department'] !== '' ? $input['department'] : null,
                'bio' => $input['bio'] !== '' ? $input['bio'] : null,
                'avatar_path' => $avatarPath
            ];
            $this->usersRepository->updateProfile($student->id, $data);
            $student = $this->usersRepository->findById($student->id) ?? $student;
            $_SESSION['founderName'] = $student->full_name ?? '';
            $_SESSION['founderAvatar'] = $student->avatar_path ?? '';
            $_SESSION['founderEmail'] = $student->email ?? '';
            $success = 'Profile updated successfully.';
        }

        $formData = $this->buildProfileFormData($student, $input);
        $this->renderProfile($student, $formData, $errors, $success);
    }

    public function logout() {
        $this->authService->logoutFounder();
        header('Location: index.php?' . http_build_query(['route' => 'showcase']));
        exit;
    }

    private function requireStudent() {
        $studentId = $this->authService->getFounderUserId();
        if (empty($studentId)) {
            header('Location: index.php?' . http_build_query(['route' => 'student/login']));
            exit;
        }

        $student = $this->usersRepository->findById($studentId);
        if (!empty($student) && !empty($student->is_restricted)) {
            $this->authService->logoutFounder();
            header('Location: index.php?' . http_build_query(['route' => 'student/login']));
            exit;
        }

        return $student;
    }

    private function buildProfileFormData($student, array $input): array {
        return [
            'full_name' => $input['full_name'] ?? ($student->full_name ?? ''),
            'year_of_study' => $input['year_of_study'] ?? ($student->year_of_study ?? ''),
            'department' => $input['department'] ?? ($student->department ?? ''),
            'bio' => $input['bio'] ?? ($student->bio ?? ''),
        ];
    }

    private function handleAvatarUpload($file, array &$errors): ?string {
        if (empty($file) || !is_array($file) || empty($file['name'])) {
            return null;
        }

        if (!empty($file['error']) && $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Failed to upload avatar.';
            return null;
        }

        $maxBytes = 2 * 1024 * 1024;
        if (!empty($file['size']) && (int) $file['size'] > $maxBytes) {
            $errors[] = 'Avatar must be 2MB or less.';
            return null;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($extension, $allowedExtensions, true)) {
            $errors[] = 'Avatar must be a JPG, PNG, or WEBP image.';
            return null;
        }

        $mimeType = null;
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if (!empty($finfo)) {
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
            }
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!empty($mimeType) && !in_array($mimeType, $allowedMimes, true)) {
            $errors[] = 'Avatar must be a valid image file.';
            return null;
        }

        $uploadDir = __DIR__ . '/../../../uploads/avatars';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $safeName = bin2hex(random_bytes(16)) . '.' . $extension;
        $destination = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $errors[] = 'Failed to save avatar upload.';
            return null;
        }

        return 'uploads/avatars/' . $safeName;
    }

    private function renderProfile($student, array $formData, array $errors, ?string $success): void {
        ob_start();
        require __DIR__ . '/../../../views/student/profile-edit.view.php';
        $contents = ob_get_clean();

        require __DIR__ . '/../../../views/layouts/public.view.php';
    }

    private function logStartupSubmission(string $stage, int $studentId, array $extra = []): void {
        $logDir = __DIR__ . '/../../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logPath = $logDir . DIRECTORY_SEPARATOR . 'app.log';

        $payload = [
            'stage' => $stage,
            'route' => $_GET['route'] ?? null,
            'method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'student_id' => $studentId,
            'post_keys' => array_keys($_POST ?? []),
            'files' => $this->collectFileMeta($_FILES ?? []),
        ];

        if (!empty($extra)) {
            $payload = array_merge($payload, $extra);
        }

        error_log(date('c') . ' ' . json_encode($payload) . PHP_EOL, 3, $logPath);
    }

    private function collectFileMeta(array $files): array {
        if (empty($files)) {
            return [];
        }

        $summary = [];
        foreach ($files as $name => $info) {
            if (!is_array($info)) {
                continue;
            }

            if (is_array($info['name'] ?? null)) {
                $count = count($info['name']);
                $entries = [];
                for ($i = 0; $i < $count; $i++) {
                    $entries[] = [
                        'name' => $info['name'][$i] ?? null,
                        'size' => $info['size'][$i] ?? null,
                        'error' => $info['error'][$i] ?? null,
                    ];
                }
                $summary[$name] = $entries;
            }
            else {
                $summary[$name] = [
                    'name' => $info['name'] ?? null,
                    'size' => $info['size'] ?? null,
                    'error' => $info['error'] ?? null,
                ];
            }
        }

        return $summary;
    }

    private function slugify(string $name): string {
        $slug = strtolower($name);
        $slug = str_replace(['/', ' ', '.'], ['-', '-', '-'], $slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        return trim($slug, '-');
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
