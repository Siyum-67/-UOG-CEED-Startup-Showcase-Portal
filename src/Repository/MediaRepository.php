<?php

namespace App\Repository;

use PDO;
use App\Model\MediaModel;

class MediaRepository {

    public function __construct(private PDO $pdo) {}

    public function getByStartupId(int $startupId): array {
        $stmt = $this->pdo->prepare('SELECT * FROM `media` WHERE `startup_id` = :startup_id ORDER BY `created_at` DESC');
        $stmt->bindValue(':startup_id', $startupId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, MediaModel::class);
    }

    public function create(int $startupId, string $type, string $path): void {
        $stmt = $this->pdo->prepare('INSERT INTO `media` (`startup_id`, `type`, `path`) VALUES (:startup_id, :type, :path)');
        $stmt->bindValue(':startup_id', $startupId, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':path', $path);
        $stmt->execute();
    }

    public function deleteByStartupId(int $startupId): void {
        $stmt = $this->pdo->prepare('DELETE FROM `media` WHERE `startup_id` = :startup_id');
        $stmt->bindValue(':startup_id', $startupId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
