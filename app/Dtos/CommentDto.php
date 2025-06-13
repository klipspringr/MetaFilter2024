<?php

declare(strict_types=1);

namespace App\Dtos;

readonly class CommentDto
{
    public function __construct(
        public string $text,
        public int $post_id,
        public int $user_id,
        public ?int $parent_id = null,
    ) {}
}
