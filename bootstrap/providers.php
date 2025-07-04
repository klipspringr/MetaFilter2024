<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\BladeServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\SvgIconServiceProvider;
use App\Providers\TelescopeServiceProvider;
use App\Providers\ViewComposerServiceProvider;

return [
    AdminPanelProvider::class,
    AppServiceProvider::class,
    BladeServiceProvider::class,
    FortifyServiceProvider::class,
    HorizonServiceProvider::class,
    RepositoryServiceProvider::class,
    SvgIconServiceProvider::class,
    TelescopeServiceProvider::class,
    ViewComposerServiceProvider::class,
];
