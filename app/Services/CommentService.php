<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\CommentDto;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;
use App\Traits\LoggingTrait;
use Exception;

final class CommentService
{
    use LoggingTrait;

    public function __construct(
        protected CommentRepositoryInterface $commentRepository,
        protected PurifierService $purifierService,
    ) {}

    public function store(CommentDto $dto): ?Comment
    {
        try {
            $data = [
                'text' => $this->purifierService->clean($dto->text),
                'post_id' => $dto->post_id,
                'user_id' => $dto->user_id,
                'parent_id' => $dto->parent_id,
            ];

            return $this->commentRepository->create($data);
        } catch (Exception $exception) {
            $this->logError($exception);

            return null;
        }
    }

    public function update(int $commentId, array $data): bool
    {
        $data['text'] = $this->purifierService->clean($data['text']);

        return $this->commentRepository->update($commentId, $data);
    }

    public function delete(int $commentId): bool
    {
        return $this->commentRepository->delete($commentId);
    }
}
