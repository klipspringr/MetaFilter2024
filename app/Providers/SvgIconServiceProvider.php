<?php

declare(strict_types=1);

namespace App\Providers;

use App\View\Components\Icons\SvgIconRegistry;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

final class SvgIconServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::addLocation(SvgIconRegistry::getBladePath());
    }

    public function register(): void
    {
        $this->app->singleton(SvgIconRegistry::class);
    }
}
