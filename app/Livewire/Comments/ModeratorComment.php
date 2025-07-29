<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\CommentStateEnum;
use App\Enums\ModerationTypeEnum;
use App\Traits\CommentComponentTrait;
use App\Traits\CommentComponentStateTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class ModeratorComment extends Component
{
    use CommentComponentStateTrait;
    use CommentComponentTrait;

    #[Locked]
    public bool $showModeratorToggle = false;

    public function render(): View
    {
        $moderationAction = match ($this->comment->moderation_type) {
            ModerationTypeEnum::Blur => 'blurred',
            ModerationTypeEnum::Comment => 'commented',
            ModerationTypeEnum::Edit => 'edited',
            ModerationTypeEnum::Remove => 'removed',
            ModerationTypeEnum::Replace => 'replaced',
            ModerationTypeEnum::Wrap => 'wrapped',
            ModerationTypeEnum::Restore => 'restored',
            default => '',
        };

        return view('livewire.comments.moderator-comment', [
            'comment' => $this->comment,
            'isModerating' => $this->state === CommentStateEnum::Moderating,
            'moderationAction' => $moderationAction,
            'moderationClass' => 'moderator-' . $moderationAction,
        ]);
    }
}
