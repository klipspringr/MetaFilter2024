<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\Music\ChallengesController;
use App\Http\Controllers\Music\ChartsController;
use App\Http\Controllers\Music\TalkController;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(MyPostController::class)
        ->prefix('my-posts')
        ->group(function () {
            Route::get('', 'index')
                ->name(RouteNameEnum::MusicMyPostsIndex);

            Route::get('{post}/{slug}', 'show')
                ->name(RouteNameEnum::MusicMyPostsShow);
        });
});

Route::get('charts', [ChartsController::class, 'index'])
    ->name('music.charts.index');

Route::get('challenges', [ChallengesController::class, 'index'])
    ->name('music.challenges.index');

Route::get('talk', [TalkController::class, 'index'])
    ->name('music.talk.index');

Route::controller(PostController::class)->group(function () {

    Route::get('', 'index')
        ->name('music.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('music.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('music.posts.create');

        Route::post('store', 'store')
            ->name('music.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('music.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('music.posts.edit');

        Route::post('update', 'update')
            ->name('music.posts.update');
    });
});
