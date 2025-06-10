<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleNameEnum;
use App\Presenters\UserPresenter;
use App\States\User\UserState;
use Coderflex\LaravelPresenter\Concerns\CanPresent;
use Coderflex\LaravelPresenter\Concerns\UsesPresenters;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Overtrue\LaravelVote\Traits\Voter;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property int $legacy_id
 * @property string $username
 * @property string $state
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 *
 * @mixin Builder
 */
final class User extends Authenticatable implements
    BannableInterface,
    CanPresent,
    FilamentUser,
    HasMedia,
    HasName
{
    use Bannable;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use HasStates;
    use InteractsWithMedia;
    use Notifiable;
    use SoftDeletes;
    use UsesPresenters;
    use Voter;

    // Properties

    protected $fillable = [
        'legacy_id',
        'username',
        'state',
        'name',
        'email',
        'email_verified_at',
        'homepage_url',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected array $presenters = [
        'default' => UserPresenter::class,
    ];

    protected array $searchable = [
        'username',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'state' => UserState::class,
        ];
    }

    public function toSearchableArray(): array
    {
        return ['id' => (string) $this->id] + $this->toArray();
    }

    // Builders
    /*
        public function newEloquentBuilder($query): UserQueryBuilder
        {
            return new UserQueryBuilder($query);
        }
    */
    // Relationships

    public function comments(): HasMany
    {
        return $this->hasMany(
            related: Comment::class,
        );
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(RoleNameEnum::MODERATOR->value);
    }
}
