<?php

namespace App\Repository;

use PDO;
use App\Model\CommentModel;

class CommentsRepository {

    public function __construct(private PDO $pdo) {}

    public function getByStartupId(int $startupId): array {
        $stmt = $this->pdo->prepare('SELECT * FROM `comments` WHERE `startup_id` = :startup_id ORDER BY `created_at` DESC');
        $stmt->bindValue(':startup_id', $startupId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, CommentModel::class);
    }

    public function getAllWithStartup(): array {
        $stmt = $this->pdo->prepare(
            'SELECT c.*, s.name AS startup_name
            FROM `comments` c
            INNER JOIN `startups` s ON s.id = c.startup_id
            ORDER BY c.created_at DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $startupId, string $authorName, string $body): void {
        $stmt = $this->pdo->prepare('INSERT INTO `comments` (`startup_id`, `author_name`, `body`) VALUES (:startup_id, :author_name, :body)');
        $stmt->bindValue(':startup_id', $startupId, PDO::PARAM_INT);
        $stmt->bindValue(':author_name', $authorName);
        $stmt->bindValue(':body', $body);
        $stmt->execute();
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM `comments` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
