<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Comment;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    public function getCommentByUserId(int $userId);

    public function getCommentsByPostId(int $postId, ?Comment $latestComment = null);

    public function getCommentsByParentId(int $parentId);
}
