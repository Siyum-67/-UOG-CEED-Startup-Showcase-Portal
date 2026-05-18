<?php

namespace App\Frontend\Controller;

use App\Repository\CommentsRepository;
use App\Repository\StartupsRepository;

class CommentsController {
    public function __construct(
        private CommentsRepository $commentsRepository,
        private StartupsRepository $startupsRepository
    ) {}

    public function store() {
        $startupId = (int) ($_POST['startup_id'] ?? 0);
        $startupSlug = (string) ($_POST['startup_slug'] ?? '');
        $authorName = trim((string) ($_POST['author_name'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));

        $errors = [];
        if ($startupId <= 0) {
            $errors[] = 'Invalid startup selected.';
        }
        if ($authorName === '') {
            $errors[] = 'Your name is required.';
        }
        if ($body === '') {
            $errors[] = 'Your comment is required.';
        }

        $startup = $startupId > 0 ? $this->startupsRepository->fetchById($startupId) : null;
        if (empty($startup)) {
            $errors[] = 'Startup not found.';
        }

        $redirectSlug = $startupSlug !== '' ? $startupSlug : (!empty($startup) ? $startup->slug : '');
        if ($redirectSlug === '') {
            $redirectSlug = 'showcase';
        }

        if (!empty($errors)) {
            $_SESSION['flash_comment_error'] = $errors;
            $_SESSION['flash_comment_old'] = [
                'author_name' => $authorName,
                'body' => $body
            ];
            if ($redirectSlug === 'showcase') {
                header('Location: index.php?' . http_build_query(['route' => 'showcase']));
            }
            else {
                header('Location: index.php?' . http_build_query(['route' => 'startups/show', 'slug' => $redirectSlug]));
            }
            exit;
        }

        $this->commentsRepository->create($startupId, $authorName, $body);
        $_SESSION['flash_comment_success'] = 'Comment submitted successfully.';
        header('Location: index.php?' . http_build_query(['route' => 'startups/show', 'slug' => $redirectSlug]));
        exit;
    }
}
