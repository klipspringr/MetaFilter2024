<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\CommentStateEnum;
use Livewire\Attributes\Reactive;

trait CommentComponentStateTrait
{
    // State
    #[Reactive]
    public CommentStateEnum $state = CommentStateEnum::Viewing;
}
