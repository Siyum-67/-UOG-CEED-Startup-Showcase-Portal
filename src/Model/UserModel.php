<?php

namespace App\Model;

class UserModel {
    public int $id;
    public ?string $username;
    public string $email;
    public string $role;
    public int $is_restricted;
    public ?string $full_name;
    public ?int $year_of_study;
    public ?string $department;
    public ?string $bio;
    public ?string $avatar_path;
    public string $created_at;
}
