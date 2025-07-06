<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\CommentStateEnum;
use App\Traits\CommentComponentTrait;
use App\Traits\CommentComponentStateTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class CommentContent extends Component
{
    use CommentComponentStateTrait;
    use CommentComponentTrait;

    public function render(): View
    {
        $data = [
            'authorizedUserId' => auth()->id() ?? null,
            'comment' => $this->comment,
            'body' => $this->comment->body,
            'flagCount' => $this->flagCount,
            'userFlagged' => $this->userFlagged,
            'isEditing' => $this->state === CommentStateEnum::Editing,
            'isFlagging' => $this->state === CommentStateEnum::Flagging,
            'isReplying' => $this->state === CommentStateEnum::Replying,
            'isModerating' => $this->state === CommentStateEnum::Moderating,
        ];

        // If there are no decorations to apply, just render the basic comment component.
        return view('livewire.comments.comment-content', $data);
    }
}
