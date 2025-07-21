<?php

declare(strict_types=1);

namespace App\Enums;

enum CommentStateEnum: string
{
    case Viewing = 'viewing';
    case Editing = 'editing';
    case Flagging = 'flagging';
    case Replying = 'replying';
    case Moderating = 'moderating';
}
