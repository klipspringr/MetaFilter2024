<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Dtos\CommentDto;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class CommentForm extends Form
{
    public ?Comment $comment = null;

    #[Validate('required|min:5')]
    public string $body = '';

    public function setComment(Comment $comment): void
    {
        $this->comment = $comment;
        $this->body = $comment->body;
    }

    public function store(CommentService $commentService): void
    {
        try {
            $this->validate();

            $dto = new CommentDto(
                text: $this->body,
                post_id: $this->postId, // TODO: Need to set postId property
                user_id: auth()->id(),
                parent_id: null,
            );

            $commentService->store($dto);
        } catch (ValidationException $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function update(): void
    {
        try {
            $this->validate();

            $this->comment->update(
                $this->all(),
            );
        } catch (ValidationException $exception) {
            Log::error($exception->getMessage());
        }
    }
}
