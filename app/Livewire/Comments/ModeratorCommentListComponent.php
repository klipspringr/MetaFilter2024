<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\CommentStateEnum;
use App\Enums\LivewireEventEnum;
use App\Repositories\CommentRepositoryInterface;
use App\Traits\AuthStatusTrait;
use App\Traits\CommentComponentStateTrait;
use App\Traits\ModeratorActionsTrait;
use App\Traits\SubsiteTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

final class ModeratorCommentListComponent extends Component
{
    use AuthStatusTrait;
    use CommentComponentStateTrait;
    use ModeratorActionsTrait;
    use SubsiteTrait;

    #[Locked]
    public int $parentId = 0;

    protected CommentRepositoryInterface $commentRepository;

    public function boot(CommentRepositoryInterface $commentRepository): void
    {
        $this->commentRepository = $commentRepository;
    }

    public function mount(int $parentId, ?Collection $childComments): void
    {
        $this->parentId = $parentId;

        if ($childComments) {
            // If provided, use the child comments from the parent comment.
            // This saves some time when all post comments were loaded up front.
            $this->childComments = $childComments;
        }
    }

    /**
     * Returns all child comments in chronological order.
     */
    #[Computed]
    public function childComments(): Collection
    {
        return $this->commentRepository->getCommentsByParentId($this->parentId);
    }

    /**
     * Returns all moderator actions in reverse chronological order.
     */
    #[Computed]
    public function moderatorActions(): Collection
    {
        return $this->filterModeratorActions($this->childComments, $this->state === CommentStateEnum::Moderating);
    }

    public function render(): View
    {
        return view('livewire.comments.moderator-comment-list-component', [
            'comments' => $this->moderatorActions,
            'isModerating' => $this->state === CommentStateEnum::Moderating,
            'isModerator' => $this->isModerator(),
        ]);
    }

    #[On([
        LivewireEventEnum::CommentStored->value,
        LivewireEventEnum::CommentDeleted->value,
        LivewireEventEnum::CommentUpdated->value,
    ])]
    public function refreshChildComments(): void
    {
        unset($this->childComments);
    }
}
