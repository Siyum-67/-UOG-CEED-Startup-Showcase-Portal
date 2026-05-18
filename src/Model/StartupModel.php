<?php

namespace App\Model;

class StartupModel {
    public int $id;
    public int $owner_id;
    public int $category_id;
    public string $name;
    public string $slug;
    public string $short_pitch;
    public string $description;
    public string $team;
    public string $stage;
    public ?string $drive_link;
    public string $created_at;
    public ?string $updated_at;
}
