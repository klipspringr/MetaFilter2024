<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Model::class => ModelPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
