<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Lubusin\Decomposer\Controllers\DecomposerController;

$appUrl = config('app.url');

$middleware = [
    'web',
];

Route::get('search', [SearchController::class, 'create'])
    ->name('search.create');

Route::get('test', [TestController::class, 'index'])
    ->name('test.index');

Route::domain('ask.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/ask.php'));

Route::domain('bestof.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/bestof.php'));

Route::domain('chat.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/chat.php'));

Route::domain('fanfare.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/fanfare.php'));

Route::domain('faq.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/faq.php'));

Route::domain('irl.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/irl.php'));

Route::domain('jobs.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/jobs.php'));

Route::domain('labs.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/labs.php'));

Route::domain('mall.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/mall.php'));

Route::domain('metatalk.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/metatalk.php'));

Route::domain('music.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/music.php'));

Route::domain('podcast.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/podcast.php'));

Route::domain('projects.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/projects.php'));

Route::domain('www.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/auth.php'));

Route::domain('www.' . $appUrl)
    ->middleware($middleware)
    ->group(base_path('routes/subdomains/metafilter.php'));

Route::get('decompose', [DecomposerController::class, 'index']);

Route::any('{any}', [PageController::class, 'show']);
