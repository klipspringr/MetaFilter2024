<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dtos\UserDto;
use App\Models\User;

final class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function createUser(UserDto $dto): User
    {
        return User::create([
            'username' => $dto->username,
            'name' => $dto->name ?? null,
            'email' => $dto->email,
            'password' => $dto->password,
            'state' => $dto->state,
        ]);
    }

    public function updateState(User $user, string $state): void
    {
        $user->state = $state;

        $user->save();
    }
}
