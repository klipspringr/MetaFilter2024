<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\MyFavoritesController;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PopularPostController;
use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\RecentCommentsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(MyPostController::class)
        ->prefix('my-posts')
        ->group(function () {
            Route::get('', 'index')
                ->name(RouteNameEnum::MetaTalkMyPostsIndex);

            Route::get('{post}/{slug}', 'show')
                ->name(RouteNameEnum::MetaTalkMyPostsShow);
        });
});

Route::get('my-favorites', [MyFavoritesController::class, 'index'])
    ->name(RouteNameEnum::MetaTalkMyFavoritesIndex);

Route::get('popular-posts', [PopularPostController::class, 'index'])
    ->name('metatalk.popular-posts.index');

Route::get('recent-comments', [RecentCommentsController::class, 'index'])
    ->name('metatalk.recent-comments.index');

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('metatalk.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('metatalk.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('metatalk.posts.create');

        Route::post('store', 'store')
            ->name('metatalk.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('metatalk.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('metatalk.posts.edit');

        Route::post('update', 'update')
            ->name('metatalk.posts.update');
    });
});
