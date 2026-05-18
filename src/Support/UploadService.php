<?php

namespace App\Support;

class UploadService {

    private const MAX_FILES = 5;
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    private const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov'];
    private const MAX_IMAGE_BYTES = 5_000_000;
    private const MAX_VIDEO_BYTES = 20_000_000;

    public function __construct(private string $uploadDir) {}

    public function processUploads(array $files): array {
        $results = [
            'errors' => [],
            'saved' => []
        ];

        $validationErrors = $this->validateUploads($files);
        if (!empty($validationErrors)) {
            $results['errors'] = $validationErrors;
            return $results;
        }

        if (empty($files['name']) || !is_array($files['name'])) {
            return $results;
        }

        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if (!empty($files['error'][$i])) {
                continue;
            }

            $originalName = $files['name'][$i];
            $tmpPath = $files['tmp_name'][$i];
            $size = (int) $files['size'][$i];

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $type = $this->detectType($extension);

            $safeName = bin2hex(random_bytes(8)) . '.' . $extension;
            $destination = rtrim($this->uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $safeName;

            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0755, true);
            }

            if (!move_uploaded_file($tmpPath, $destination)) {
                $results['errors'][] = 'Failed to upload: ' . $originalName;
                continue;
            }

            $results['saved'][] = [
                'type' => $type,
                'path' => 'uploads/' . $safeName
            ];
        }

        return $results;
    }

    public function validateUploads(array $files): array {
        $errors = [];

        if (empty($files['name']) || !is_array($files['name'])) {
            return $errors;
        }

        $count = count($files['name']);
        if ($count > self::MAX_FILES) {
            $errors[] = 'You can upload up to ' . self::MAX_FILES . ' files.';
            return $errors;
        }

        for ($i = 0; $i < $count; $i++) {
            if (!empty($files['error'][$i])) {
                continue;
            }

            $originalName = $files['name'][$i];
            $size = (int) $files['size'][$i];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $type = $this->detectType($extension);

            if ($type === null) {
                $errors[] = 'Unsupported file type: ' . $originalName;
                continue;
            }

            if ($type === 'image' && $size > self::MAX_IMAGE_BYTES) {
                $errors[] = 'Image too large: ' . $originalName;
            }

            if ($type === 'video' && $size > self::MAX_VIDEO_BYTES) {
                $errors[] = 'Video too large: ' . $originalName;
            }
        }

        return $errors;
    }

    private function detectType(string $extension): ?string {
        if (in_array($extension, self::IMAGE_EXTENSIONS, true)) {
            return 'image';
        }
        if (in_array($extension, self::VIDEO_EXTENSIONS, true)) {
            return 'video';
        }
        return null;
    }
}
