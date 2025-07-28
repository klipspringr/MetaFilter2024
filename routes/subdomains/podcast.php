<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Podcast\BestOfTheWebController;
use App\Http\Controllers\Podcast\OutOfTheBlueController;
use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(MyPostController::class)
        ->prefix('my-posts')
        ->group(function () {
            Route::get('', 'index')
                ->name(RouteNameEnum::PodcastMyPostsIndex);

            Route::get('{post}/{slug}', 'show')
                ->name(RouteNameEnum::PodcastMyPostsShow);
        });
});

Route::get('best-of-the-web', [BestOfTheWebController::class, 'index'])
    ->name('podcast.best-of-the-web.index');

Route::get('out-of-the-blue', [OutOfTheBlueController::class, 'index'])
    ->name('podcast.out-of-the-blue.index');

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('podcast.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('podcast.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('podcast.posts.create');

        Route::post('store', 'store')
            ->name('podcast.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('podcast.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('podcast.posts.edit');

        Route::post('update', 'update')
            ->name('podcast.posts.update');
    });
});
