<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\FanFare\ClubsController;
use App\Http\Controllers\FanFare\TalkController;
use App\Http\Controllers\FanFare\WaterCoolerController;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(MyPostController::class)->prefix('my-posts')->group(function () {
        Route::get('', 'index')
            ->name(RouteNameEnum::FanFareMyPostsIndex);

        Route::get('{post}/{slug}', 'show')
            ->name(RouteNameEnum::FanFareMyPostsShow);
    });
});

Route::get('clubs', [ClubsController::class, 'index'])
    ->name('fanfare.clubs.index');

Route::get('talk', [TalkController::class, 'index'])
    ->name('fanfare.talk.index');

Route::get('water-cooler', [WaterCoolerController::class, 'index'])
    ->name('fanfare.water-cooler.index');

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('fanfare.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('fanfare.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('fanfare.posts.create');

        Route::post('store', 'store')
            ->name('fanfare.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('fanfare.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('fanfare.posts.edit');

        Route::post('update', 'update')
            ->name('fanfare.posts.update');
    });
});
