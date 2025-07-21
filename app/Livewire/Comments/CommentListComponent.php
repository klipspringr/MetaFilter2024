<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\LivewireEventEnum;
use App\Repositories\CommentRepositoryInterface;
use App\Traits\AuthStatusTrait;
use App\Traits\SubsiteTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

final class CommentListComponent extends Component
{
    use AuthStatusTrait;
    use SubsiteTrait;

    #[Locked]
    public int $postId = 0;

    #[Locked]
    public string $recordsText = 'comments';

    protected CommentRepositoryInterface $commentRepository;

    public function boot(CommentRepositoryInterface $commentRepository): void
    {
        $this->commentRepository = $commentRepository;
    }

    public function mount(int $postId): void
    {
        $this->postId = $postId;

        $this->setRecordsLabel();
    }

    #[Computed]
    public function comments(): Collection
    {
        return $this->commentRepository->getCommentsByPostId($this->postId);
    }

    public function render(): View
    {
        return view('livewire.comments.comment-list-component', [
            'comments' => $this->comments,
        ]);
    }

    #[On([
        LivewireEventEnum::CommentStored->value,
        LivewireEventEnum::CommentDeleted->value,
        LivewireEventEnum::CommentUpdated->value,
    ])]
    public function getComments(): void
    {
        unset($this->comments);
    }

    private function setRecordsLabel(): void
    {
        $subdomain = $this->getSubdomain();

        $this->recordsText = match ($subdomain) {
            'ask' => trans('answers'),
            default => trans('comments'),
        };
    }
}
