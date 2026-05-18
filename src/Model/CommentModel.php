<?php

namespace App\Model;

class CommentModel {
    public int $id;
    public int $startup_id;
    public string $author_name;
    public string $body;
    public string $created_at;
}
