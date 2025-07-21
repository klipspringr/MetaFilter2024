<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\CommentStateEnum;
use App\Enums\ModerationTypeEnum;
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
        $moderatorComment = $this->moderatorCommentsByType?->get(ModerationTypeEnum::Replace->value);

        return view('livewire.comments.comment-replacement', [
            'comment' => $this->comment,
            'childComments' => $this->childComments,
            'moderatorComment' => $moderatorComment,
            'isModerating' => $this->state === CommentStateEnum::Moderating,
        ]);
    }
}
