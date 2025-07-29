<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\CommentStateEnum;
use App\Traits\CommentComponentTrait;
use App\Traits\CommentComponentStateTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class CommentWrapper extends Component
{
    use CommentComponentStateTrait;
    use CommentComponentTrait;

    // State
    public bool $isOpen = false;

    public function render(): View
    {
        return view('livewire.comments.comment-wrapper', [
            'comment' => $this->comment,
            'childComments' => $this->childComments,
            'moderatorComment' => $this->appearanceComment,
            'isInitiallyBlurred' => $this->isInitiallyBlurred,
            'isModerating' => $this->state === CommentStateEnum::Moderating,
        ]);
    }
}
