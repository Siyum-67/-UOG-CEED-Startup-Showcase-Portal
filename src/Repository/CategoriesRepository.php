<?php

namespace App\Repository;

use PDO;
use App\Model\CategoryModel;

class CategoriesRepository {

    public function __construct(private PDO $pdo) {}

    public function getAll(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM `categories` ORDER BY `name` ASC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, CategoryModel::class);
    }

    public function fetchBySlug(string $slug): ?CategoryModel {
        $stmt = $this->pdo->prepare('SELECT * FROM `categories` WHERE `slug` = :slug');
        $stmt->bindValue(':slug', $slug);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, CategoryModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }

    public function fetchById(int $id): ?CategoryModel {
        $stmt = $this->pdo->prepare('SELECT * FROM `categories` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, CategoryModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }
}
