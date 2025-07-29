<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\RoleNameEnum;

trait AuthStatusTrait
{
    public function getAuthorizedUserId(): int|null
    {
        return auth()->id();
    }

    public function isModerator(): bool
    {
        return auth()->user()?->hasRole(RoleNameEnum::MODERATOR->value) ?? false;
    }

    public function loggedIn(): bool
    {
        return auth()->check() === true;
    }

    public function loggedOut(): bool
    {
        return auth()->check() === false;
    }
}
