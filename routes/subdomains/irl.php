<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\Irl\FutureEventsController;
use App\Http\Controllers\Irl\PastEventsController;
use App\Http\Controllers\Irl\ProposedEventsController;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(MyPostController::class)->prefix('my-posts')->group(function () {
        Route::get('', 'index')
            ->name(RouteNameEnum::IrlMyPostsIndex);

        Route::get('{post}/{slug?}', 'show')
            ->whereNumber('post')
            ->name(RouteNameEnum::IrlMyPostsShow);
    });
});

Route::get('future-events', [FutureEventsController::class, 'index'])
    ->name('irl.future-events.index');

Route::get('past-events', [PastEventsController::class, 'index'])
    ->name('irl.past-events.index');

Route::get('proposed-events', [ProposedEventsController::class, 'index'])
    ->name('irl.proposed-events.index');

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('irl.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('irl.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('irl.posts.create');

        Route::post('store', 'store')
            ->name('irl.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('irl.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('irl.posts.edit');

        Route::post('update', 'update')
            ->name('irl.posts.update');
    });
});
