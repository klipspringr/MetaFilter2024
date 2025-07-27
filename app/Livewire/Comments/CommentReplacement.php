<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\CommentStateEnum;
use App\Traits\CommentComponentTrait;
use App\Traits\CommentComponentStateTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class CommentReplacement extends Component
{
    use CommentComponentStateTrait;
    use CommentComponentTrait;

    public function render(): View
    {
        return view('livewire.comments.comment-replacement', [
            'comment' => $this->comment,
            'childComments' => $this->childComments,
            'moderatorComment' => $this->appearanceComment,
            'isModerating' => $this->state === CommentStateEnum::Moderating,
        ]);
    }
}
