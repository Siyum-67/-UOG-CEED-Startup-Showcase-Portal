<?php

namespace App\Repository;

use PDO;
use App\Model\StartupModel;

class StartupsRepository {

    public function __construct(private PDO $pdo) {}

    public function getAll(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM `startups` ORDER BY `created_at` DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, StartupModel::class);
    }

    public function getAllWithOwnerAndCategory(): array {
        $stmt = $this->pdo->prepare(
            'SELECT s.*, u.email AS owner_email, c.name AS category_name
            FROM `startups` s
            INNER JOIN `users` u ON u.id = s.owner_id
            INNER JOIN `categories` c ON c.id = s.category_id
            ORDER BY s.created_at DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchBySlug(string $slug): ?StartupModel {
        $stmt = $this->pdo->prepare('SELECT * FROM `startups` WHERE `slug` = :slug');
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, StartupModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }

    public function fetchById(int $id): ?StartupModel {
        $stmt = $this->pdo->prepare('SELECT * FROM `startups` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, StartupModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }

    public function fetchByOwner(int $ownerId): array {
        $stmt = $this->pdo->prepare('SELECT * FROM `startups` WHERE `owner_id` = :owner_id ORDER BY `created_at` DESC');
        $stmt->bindValue(':owner_id', $ownerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, StartupModel::class);
    }

    public function getByCategoryId(int $categoryId): array {
        $stmt = $this->pdo->prepare('SELECT * FROM `startups` WHERE `category_id` = :category_id ORDER BY `created_at` DESC');
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, StartupModel::class);
    }

    public function getSlugExists(string $slug): bool {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS `count` FROM `startups` WHERE `slug` = :slug');
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ((int) $result['count'] >= 1);
    }

    public function create(array $data, ?array &$errorInfo = null): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO `startups` (`owner_id`, `category_id`, `name`, `slug`, `short_pitch`, `description`, `team`, `stage`, `drive_link`)
            VALUES (:owner_id, :category_id, :name, :slug, :short_pitch, :description, :team, :stage, :drive_link)'
        );
        $stmt->bindValue(':owner_id', $data['owner_id'], PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':slug', $data['slug']);
        $stmt->bindValue(':short_pitch', $data['short_pitch']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':team', $data['team']);
        $stmt->bindValue(':stage', $data['stage']);
        $stmt->bindValue(':drive_link', $data['drive_link']);
        $ok = $stmt->execute();
        if ($ok === false) {
            $errorInfo = $stmt->errorInfo();
            return 0;
        }
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            'UPDATE `startups`
            SET `category_id` = :category_id,
                `name` = :name,
                `short_pitch` = :short_pitch,
                `description` = :description,
                `team` = :team,
                `stage` = :stage,
                `drive_link` = :drive_link,
                `updated_at` = NOW()
            WHERE `id` = :id'
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':short_pitch', $data['short_pitch']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':team', $data['team']);
        $stmt->bindValue(':stage', $data['stage']);
        $stmt->bindValue(':drive_link', $data['drive_link']);
        $stmt->execute();
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM `startups` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
