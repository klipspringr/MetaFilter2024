<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\ModerationTypeEnum;
use App\Traits\CommentComponentTrait;
use App\Traits\CommentComponentStateTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class CommentBlur extends Component
{
    use CommentComponentStateTrait;
    use CommentComponentTrait;

    // State
    public bool $isBlurred = true;

    public function render(): View
    {
        $moderatorComment = $this->moderatorCommentsByType?->get(ModerationTypeEnum::Blur->value);

        return view('livewire.comments.comment-blur', [
            'comment' => $this->comment,
            'childComments' => $this->childComments,
            'blurMessage' => $moderatorComment?->body ?? '',
        ]);
    }
}
