<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\LivewireEventEnum;
use App\Models\Comment;
use App\Models\Flag;
use App\Models\Post;
use App\Models\User;
use App\Traits\CommentComponentTrait;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

final class CommentComponent extends Component
{
    use CommentComponentTrait;

    // Data
    public ?int $authorizedUserId;
    public string $body = '';
    public ?int $parentId = null;
    public int $flagCount = 0;
    public bool $userFlagged = false;

    // State
    public bool $isEditing = false;
    public bool $isFlagging = false;
    public bool $isFlagLoading = false;
    public bool $isReplying = false;

    public Comment $comment;
    public CommentForm $commentForm;
    public Post $post;
    public ?User $user;

    public function mount(Comment $comment, Post $post): void
    {
        $this->authorizedUserId = auth()->id() ?? null;

        $this->comment = $comment;
        $this->commentForm->setComment($comment);

        $this->post = $post;

        $this->body = $comment->body;

        $this->user = auth()->user() ?? null;

        $this->updateFlagCount();
    }

    public function render(): View
    {
        return view('livewire.comments.comment-component');
    }

    #[On([
        LivewireEventEnum::CommentStored->value,
        LivewireEventEnum::CommentDeleted->value,
        LivewireEventEnum::CommentUpdated->value,
        LivewireEventEnum::EscapeKeyClicked->value,
    ])]
    public function closeForm(): void
    {
        $this->reset([
            'body',
        ]);

        $this->stopEditing();
        $this->stopReplying();
    }

    private function updateFlagCount(): void
    {
        $this->flagCount = $this->comment->flagCount();
        $this->userFlagged = $this->comment->userFlagged();
    }

    public function addUserFlag(int $id): void
    {
        if ($id !== $this->comment->id) {
            return;
        }

        $this->userFlagged = true;
        // Requery as flag may have just been edited, not added
        $this->updateFlagCount();
        $this->stopFlagging();
    }

    public function removeUserFlag(int $id): void
    {
        if ($id !== $this->comment->id) {
            return;
        }

        $this->userFlagged = false;
        // Requery as technically multiple flags could exist (though they shouldn't)
        $this->updateFlagCount();
        $this->stopFlagging();
    }
}
