<?php

declare(strict_types=1);

use App\Http\Controllers\Posts\PostController;
use Illuminate\Support\Facades\Route;

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('bestof.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('bestof.posts.show');
});
