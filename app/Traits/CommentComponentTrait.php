<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\CommentStateEnum;
use App\Enums\LivewireEventEnum;
use App\Enums\ModerationTypeEnum;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;

trait CommentComponentTrait
{
    // Data
    #[Locked]
    public int $commentId = 0;

    // Provided via mount or fetched during hydration.
    protected ?Comment $comment = null;
    protected ?Collection $childComments = null;

    #[Computed]
    public function flagCount(): int
    {
        return $this->comment?->flagCount() ?? 0;
    }

    #[Computed]
    public function userFlagged(): bool
    {
        return $this->comment?->userFlagged() ?? false;
    }

    /**
     * Finds the most recent moderator remove, replace, or wrap comment.
     *
     * The effect of Reset is to override any previous appearance-modifying comment
     * and restore the default appearance for the original comment.
     */
    #[Computed]
    public function appearanceComment(): ?Comment
    {
        $appearanceComment = $this->childComments?->last(
            fn($comment) => $comment->moderation_type !== null && match ($comment->moderation_type) {
                ModerationTypeEnum::Remove, ModerationTypeEnum::Replace, ModerationTypeEnum::Wrap, ModerationTypeEnum::Restore => true,
                default => false,
            },
        );

        // If the last appearance-modifying comment is a Restore, return null.
        if ($appearanceComment && $appearanceComment->moderation_type === ModerationTypeEnum::Restore) {
            return null;
        }

        return $appearanceComment;
    }

    /**
     * Finds the most recent moderator blur comment.
     *
     * Blurring of comments can coexist with wrapping, but not with removal or replacement.
     * It is also reset by a later Restore comment.
     */
    #[Computed]
    public function blurComment(): ?Comment
    {
        $blurComment = $this->childComments?->last(
            fn($comment) => $comment->moderation_type !== null && match ($comment->moderation_type) {
                ModerationTypeEnum::Blur, ModerationTypeEnum::Remove, ModerationTypeEnum::Replace, ModerationTypeEnum::Restore => true,
                default => false,
            },
        );

        // If the last blur-modifying comment is not a Blur, return null.
        if ($blurComment && $blurComment->moderation_type !== ModerationTypeEnum::Blur) {
            return null;
        }

        return $blurComment;
    }

    #[Computed]
    public function isInitiallyBlurred(): bool
    {
        return $this->blurComment !== null;
    }

    protected CommentRepositoryInterface $commentRepository;

    public function bootCommentComponentTrait(CommentRepositoryInterface $commentRepository): void
    {
        $this->commentRepository = $commentRepository;
    }

    public function mountCommentComponentTrait(int $commentId, ?Comment $comment, ?Collection $childComments): void
    {
        // On mount we expect the comment list to provide the comment model
        // and the moderator comments collection.
        $this->commentId = $commentId;
        $this->comment = $comment ?? Comment::find($commentId);
        $this->childComments = $childComments;
    }

    public function hydrateCommentComponentTrait(): void
    {
        // On subsequent requests, we need to re-fetch the comment and moderator comments.
        $this->comment = $this->commentId ? Comment::find($this->commentId) : null;
        $this->childComments = $this->commentRepository->getCommentsByParentId($this->commentId);
    }


    public function toggleEditing(): void
    {
        if ($this->state === CommentStateEnum::Editing) {
            $this->stopEditing();
        } else {
            $this->startEditing();
        }
    }

    public function toggleFlagging(): void
    {
        if ($this->state === CommentStateEnum::Flagging) {
            $this->stopFlagging();
        } else {
            $this->startFlagging();
        }
    }

    public function toggleReplying(): void
    {
        if ($this->state === CommentStateEnum::Replying) {
            $this->stopReplying();
        } else {
            $this->startReplying();
        }
    }

    public function toggleModerating(): void
    {
        if ($this->state === CommentStateEnum::Moderating) {
            $this->stopModerating();
        } else {
            $this->startModerating();
        }
    }

    public function requestStateChange(CommentStateEnum $state): void
    {
        $this->dispatch(LivewireEventEnum::CommentFormStateChanged->value, id: $this->commentId, state: $state);
    }

    public function startEditing(): void
    {
        $this->requestStateChange(CommentStateEnum::Editing);
    }

    public function stopEditing(): void
    {
        $this->requestStateChange(CommentStateEnum::Viewing);
    }

    public function startFlagging(): void
    {
        $this->requestStateChange(CommentStateEnum::Flagging);
    }

    public function stopFlagging(): void
    {
        $this->requestStateChange(CommentStateEnum::Viewing);
    }

    public function startReplying(): void
    {
        $this->requestStateChange(CommentStateEnum::Replying);
    }

    public function stopReplying(): void
    {
        $this->requestStateChange(CommentStateEnum::Viewing);
    }

    public function startModerating(): void
    {
        $this->requestStateChange(CommentStateEnum::Moderating);
    }

    public function stopModerating(): void
    {
        $this->requestStateChange(CommentStateEnum::Viewing);
    }
}
