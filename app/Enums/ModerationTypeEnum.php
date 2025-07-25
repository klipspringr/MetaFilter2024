<?php

declare(strict_types=1);

namespace App\Enums;

enum ModerationTypeEnum: string
{
    case Blur = 'blur';
    case Comment = 'comment';
    case Edit = 'edit';
    case Remove = 'remove';
    case Replace = 'replace';
    case Restore = 'restore';
    case Wrap = 'wrap';

    public function label(): string
    {
        return match ($this) {
            self::Blur => 'Blur',
            self::Comment => 'Comment',
            self::Edit => 'Edit',
            self::Remove => 'Remove',
            self::Replace => 'Replace',
            self::Restore => 'Restore',
            self::Wrap => 'Wrap',
        };
    }
}
