<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\Jobs\PostJobController;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('create', [PostJobController::class, 'create'])
        ->name('jobs.posts.create');

    Route::controller(MyPostController::class)->prefix('my-posts')->group(function () {
        Route::get('', 'index')
            ->name(RouteNameEnum::JobsMyPostsIndex);

        Route::get('{post}/{slug}', 'show')
            ->name(RouteNameEnum::JobsMyPostsShow);
    });
});

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('jobs.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('jobs.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('jobs.posts.create');

        Route::post('store', 'store')
            ->name('jobs.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('jobs.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('jobs.posts.edit');

        Route::post('update', 'update')
            ->name('jobs.posts.update');
    });
});
