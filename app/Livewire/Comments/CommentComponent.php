<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\CommentStateEnum;
use App\Enums\LivewireEventEnum;
use App\Enums\ModerationTypeEnum;
use App\Models\Comment;
use App\Traits\CommentComponentTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

final class CommentComponent extends Component
{
    use CommentComponentTrait;

    // State
    public CommentStateEnum $state = CommentStateEnum::Viewing;

    #[Computed]
    public function isInitiallyBlurred(): bool
    {
        return $this->moderatorCommentsByType?->get(ModerationTypeEnum::Blur->value) !== null;
    }

    public function mount(int $commentId, ?Comment $comment, ?Collection $childComments): void
    {
        // On mount we expect the comment list to provide the comment model
        // and the moderator comments collection.
        $this->commentId = $commentId;
        $this->comment = $comment ?? Comment::find($commentId);
        $this->childComments = $childComments;

        // Decide whether to blur the component initially.
        $this->isBlurred = $this->isInitiallyBlurred;
    }

    public function render(): View
    {
        // If the comment has been replaced, just render the moderation message.
        // TODO: if it is possible to have a top-level comment be marked with a
        // moderation type, we may want to render it via this view. But it may
        // also just be about changing the border for a regular rendering.
        $moderatorReplaceComment = $this->moderatorCommentsByType?->get(ModerationTypeEnum::Replace->value);
        $moderatorWrapComment = $this->moderatorCommentsByType?->get(ModerationTypeEnum::Wrap->value);
        $moderatorBlurComment = $this->moderatorCommentsByType?->get(ModerationTypeEnum::Blur->value);
        $moderationType = null;

        if ($moderatorReplaceComment !== null &&
            ($moderatorWrapComment === null || $moderatorReplaceComment->created_at > $moderatorWrapComment->created_at)) {
            $moderationType = ModerationTypeEnum::Replace;
        } elseif ($moderatorWrapComment !== null) {
            $moderationType = ModerationTypeEnum::Wrap;
        }

        // If there are no decorations to apply, just render the basic comment component.
        return view('livewire.comments.comment-component', [
            'comment' => $this->comment,
            'childComments' => $this->childComments,
            'moderationType' => $moderationType,
            'isInitiallyBlurred' => $this->isInitiallyBlurred,
            'replacedByCommentId' => $moderatorReplaceComment?->id,
            'wrappedByCommentId' => $moderatorWrapComment?->id,
            'blurredByCommentId' => $moderatorBlurComment?->id,
            'isEditing' => $this->state === CommentStateEnum::Editing,
            'isFlagging' => $this->state === CommentStateEnum::Flagging,
            'isReplying' => $this->state === CommentStateEnum::Replying,
            'isModerating' => $this->state === CommentStateEnum::Moderating,
        ]);
    }

    #[On([
        LivewireEventEnum::EscapeKeyClicked->value,
    ])]
    public function closeForm(): void
    {
        $this->state = CommentStateEnum::Viewing;
    }

    #[On([
        LivewireEventEnum::CommentStored->value,
        LivewireEventEnum::CommentUpdated->value,
    ])]
    public function reloadChildComments(int $id, ?int $parentId): void
    {
        if ($parentId === $this->commentId) {
            $this->childComments = $this->commentRepository->getCommentsByParentId($parentId);
            unset($this->moderatorCommentsByType, $this->isInitiallyBlurred);

            // Re-evaluate whether the comment should be blurred.
            $this->isBlurred = $this->isInitiallyBlurred;
        }
    }

    public function setState(CommentStateEnum $state): void
    {
        $this->state = $state;
    }

    public function addUserFlag(int $id): void
    {
        if ($id !== $this->comment->id) {
            return;
        }

        unset($this->userFlagged, $this->flagCount);

        $this->state = CommentStateEnum::Viewing;
    }

    public function removeUserFlag(int $id): void
    {
        if ($id !== $this->comment->id) {
            return;
        }

        unset($this->userFlagged, $this->flagCount);

        $this->state = CommentStateEnum::Viewing;
    }
}
