<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

final class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    private const array COLUMNS = [
        'comments.id',
        'comments.body',
        'comments.post_id',
        'comments.parent_id',
        'comments.moderation_type',
        'comments.user_id',
        'comments.created_at',
        'comments.deleted_at',
        'users.username',
    ];

    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function getCommentByUserId(int $userId): int
    {
        return $this->model
            ->where('user_id', '=', $userId)
            ->count();
    }

    public function getCommentsByPostId(int $postId, ?Comment $latestComment = null): Collection
    {
        $query = $this->model->newQuery()
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select(self::COLUMNS)
            ->with([
                'user',
            ])
            ->where('comments.post_id', '=', $postId);

        if ($latestComment !== null) {
            $query->where('comments.created_at', '>', $latestComment->created_at);
        }

        $query->orderBy('comments.created_at');

        return $query->get();
    }

    public function getCommentsByParentId(int $parentId): Collection
    {
        $query = $this->model->newQuery()
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select(self::COLUMNS)
            ->with([
                'user',
            ])
            ->where('comments.parent_id', '=', $parentId);

        $query->orderBy('comments.created_at');

        return $query->get();
    }
}
