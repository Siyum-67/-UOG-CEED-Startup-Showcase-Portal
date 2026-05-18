<?php

namespace App\Admin\Support;

use PDO;

class AuthService {

    public function __construct(private PDO $pdo) {}

    private function ensureSession() {
        if (session_id() === '') {
            session_start();
        }
    }

    private array $allowedDomains = [
        'uog.edu',
        'uog.edu.et'
    ];

    public function logoutAdmin() {
        $this->ensureSession();
        unset($_SESSION['adminUserId']);
        session_regenerate_id();
    }

    public function logoutFounder() {
        $this->ensureSession();
        unset($_SESSION['founderUserId']);
        unset($_SESSION['founderName'], $_SESSION['founderAvatar'], $_SESSION['founderEmail']);
        session_regenerate_id();
    }

    public function handleAdminLogin(string $username, string $password): bool {
        if (empty($username)) return false;
        if (empty($password)) return false;

        $stmt = $this->pdo->prepare(
            'SELECT `id`, `password`, `is_restricted` FROM `users` WHERE `username` = :username AND `role` = :role'
        );
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':role', 'admin');
        $stmt->execute();
        $entry = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($entry) || !empty($entry['is_restricted'])) {
            return false;
        }

        $hash = $entry['password'];
        $passwordOk = password_verify($password, $hash);
        if (empty($passwordOk)) {
            return false;
        }

        $this->ensureSession();
        $_SESSION['adminUserId'] = $entry['id'];
        session_regenerate_id();

        return true;
    }

    public function handleFounderLogin(string $email, string $password): bool {
        if (empty($email)) return false;
        if (empty($password)) return false;

        $stmt = $this->pdo->prepare(
            'SELECT `id`, `password`, `is_restricted` FROM `users` WHERE `email` = :email AND `role` = :role'
        );
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':role', 'founder');
        $stmt->execute();
        $entry = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($entry) || !empty($entry['is_restricted'])) {
            return false;
        }

        $hash = $entry['password'];
        $passwordOk = password_verify($password, $hash);
        if (empty($passwordOk)) {
            return false;
        }

        $this->ensureSession();
        $_SESSION['founderUserId'] = $entry['id'];
        $stmt = $this->pdo->prepare('SELECT `email`, `full_name`, `avatar_path` FROM `users` WHERE `id` = :id');
        $stmt->bindValue(':id', $entry['id'], PDO::PARAM_INT);
        $stmt->execute();
        $profile = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $_SESSION['founderEmail'] = $profile['email'] ?? $email;
        $_SESSION['founderName'] = $profile['full_name'] ?? '';
        $_SESSION['founderAvatar'] = $profile['avatar_path'] ?? '';
        session_regenerate_id();

        return true;
    }

    public function registerFounder(string $email, string $password): bool {
        if (empty($email) || empty($password)) {
            return false;
        }

        if (!$this->isAllowedDomain($email)) {
            return false;
        }

        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS `count` FROM `users` WHERE `email` = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($result['count'])) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            'INSERT INTO `users` (`email`, `password`, `role`) VALUES (:email, :password, :role)'
        );
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hash);
        $stmt->bindValue(':role', 'founder');
        $stmt->execute();
        return true;
    }

    public function isAdminLoggedIn(): bool {
        $this->ensureSession();
        return !empty($_SESSION['adminUserId']);
    }

    public function getAdminUserId(): ?int {
        $this->ensureSession();
        return !empty($_SESSION['adminUserId']) ? (int) $_SESSION['adminUserId'] : null;
    }

    public function isFounderLoggedIn(): bool {
        $this->ensureSession();
        return !empty($_SESSION['founderUserId']);
    }

    public function ensureAdminLoggedIn() {
        if (!$this->isAdminLoggedIn()) {
            header('Location: index.php?' . http_build_query(['route' => 'admin/login']));
            die();
        }
    }

    public function ensureFounderLoggedIn() {
        if (!$this->isFounderLoggedIn()) {
            header('Location: index.php?' . http_build_query(['route' => 'account/login']));
            die();
        }
    }

    public function getFounderUserId(): ?int {
        $this->ensureSession();
        return !empty($_SESSION['founderUserId']) ? (int) $_SESSION['founderUserId'] : null;
    }

    private function isAllowedDomain(string $email): bool {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return false;
        }
        $domain = strtolower($parts[1]);
        return in_array($domain, $this->allowedDomains, true);
    }
}