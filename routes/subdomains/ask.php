<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\Ask\AnsweredQuestionsController;
use App\Http\Controllers\Ask\UnansweredQuestionsController;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PopularPostController;
use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(MyPostController::class)->prefix('my-posts')->group(function () {
        Route::get('', 'index')
            ->name(RouteNameEnum::AskMyPostsIndex);

        Route::get('{post}/{slug}', 'show')
            ->name(RouteNameEnum::AskMyPostsShow);
    });
});

Route::get('answered-questions', [AnsweredQuestionsController::class, 'index'])
    ->name('ask.answered-questions.index');

Route::get('popular-questions', [PopularPostController::class, 'index'])
    ->name('ask.popular-questions.index');

Route::get('unanswered-questions', [UnansweredQuestionsController::class, 'index'])
    ->name('ask.unanswered-questions.index');

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('ask.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('ask.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('ask.posts.create');

        Route::post('store', 'store')
            ->name('ask.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('ask.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('ask.posts.edit');

        Route::post('update', 'update')
            ->name('ask.posts.update');
    });
});
