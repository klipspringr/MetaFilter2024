<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\PostDto;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;
use App\Traits\LoggingTrait;
use Exception;

class PostService
{
    use LoggingTrait;

    public function __construct(
        protected PostRepositoryInterface $postRepository,
        protected PurifierService $purifierService,
        protected TagService $tagService,
    ) {}

    public function closePost(Post $post): void
    {
        $this->postRepository->closePost($post);
    }

    public function store(PostDto $dto): ?Post
    {
        try {
            $data = [
                'title' => $this->purifierService->clean($dto->title),
                'body' => $this->purifierService->clean($dto->body),
                'more_inside' => $this->purifierService->clean($dto->more_inside),
                'user_id' => $dto->user_id,
                'subsite_id' => $dto->subsite_id,
                'published_at' => $dto->published_at,
                'is_published' => $dto->is_published,
            ];

            $post = $this->postRepository->create($data);

            $this->tagService->addTagsToPost($post, $dto->tags);

            return $post;
        } catch (Exception $exception) {
            $this->logError($exception);

            return null;
        }
    }

    public function update(int $id, array $data): bool
    {
        return $this->postRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->postRepository->delete($id);
    }
}
