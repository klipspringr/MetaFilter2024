<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(MyPostController::class)->prefix('my-posts')->group(function () {
        Route::get('', 'index')
            ->name(RouteNameEnum::ProjectsMyPostsIndex);

        Route::get('{post}/{slug}', 'show')
            ->name(RouteNameEnum::ProjectsMyPostsShow);
    });
});

Route::controller(PostController::class)
    ->group(function () {
        Route::get('', 'index')
            ->name('projects.posts.index');

        Route::get('{post}/{slug?}', 'show')
            ->whereNumber('post')
            ->name('projects.posts.show');

        Route::middleware('auth')->group(function () {
            Route::get('create', 'create')
                ->name('projects.posts.create');

            Route::post('store', 'store')
                ->name('projects.posts.store');

            Route::get('preview/{post}', 'preview')
                ->name('projects.posts.preview');

            Route::get('edit/{post}', 'edit')
                ->name('projects.posts.edit');

            Route::post('update', 'update')
                ->name('projects.posts.update');
        });
    });
