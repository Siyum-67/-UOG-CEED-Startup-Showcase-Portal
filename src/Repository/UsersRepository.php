<?php

namespace App\Repository;

use PDO;
use App\Model\UserModel;

class UsersRepository {

    public function __construct(private PDO $pdo) {}

    public function getAll(): array {
        $stmt = $this->pdo->prepare('SELECT * FROM `users` ORDER BY `created_at` DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, UserModel::class);
    }

    public function findByEmail(string $email): ?UserModel {
        $stmt = $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, UserModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }

    public function findById(int $id): ?UserModel {
        $stmt = $this->pdo->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, UserModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }

    public function findByUsername(string $username): ?UserModel {
        $stmt = $this->pdo->prepare('SELECT * FROM `users` WHERE `username` = :username');
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, UserModel::class);
        $entry = $stmt->fetch();
        return $entry ?: null;
    }

    public function createFounder(string $email, string $passwordHash): int {
        $stmt = $this->pdo->prepare('INSERT INTO `users` (`email`, `password`, `role`) VALUES (:email, :password, :role)');
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $passwordHash);
        $stmt->bindValue(':role', 'founder');
        $stmt->execute();
        return (int) $this->pdo->lastInsertId();
    }

    public function setRestricted(int $id, bool $restricted): void {
        $stmt = $this->pdo->prepare('UPDATE `users` SET `is_restricted` = :restricted WHERE `id` = :id');
        $stmt->bindValue(':restricted', $restricted ? 1 : 0, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setRole(int $id, string $role): void {
        $stmt = $this->pdo->prepare('UPDATE `users` SET `role` = :role WHERE `id` = :id');
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateProfile(int $id, array $data): void {
        $stmt = $this->pdo->prepare(
            'UPDATE `users`
            SET `full_name` = :full_name,
                `year_of_study` = :year_of_study,
                `department` = :department,
                `bio` = :bio,
                `avatar_path` = :avatar_path
            WHERE `id` = :id'
        );
        $stmt->bindValue(':full_name', $data['full_name']);
        $stmt->bindValue(':year_of_study', $data['year_of_study'], $data['year_of_study'] === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':department', $data['department']);
        $stmt->bindValue(':bio', $data['bio']);
        $stmt->bindValue(':avatar_path', $data['avatar_path']);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
