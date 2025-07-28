<?php

declare(strict_types=1);

use App\Enums\RouteNameEnum;
use App\Http\Controllers\Posts\ArchivesController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\FundingController;
use App\Http\Controllers\MeFiMailController;
use App\Http\Controllers\MyCommentsController;
use App\Http\Controllers\MyFavoritesController;
use App\Http\Controllers\MyMeFiController;
use App\Http\Controllers\Posts\MyPostController;
use App\Http\Controllers\Posts\PopularPostController;
use App\Http\Controllers\Posts\PostController;
use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Posts\RandomPostController;
use App\Http\Controllers\RecentActivityController;
use App\Http\Controllers\RecentCommentsController;
use App\Http\Controllers\Sitemaps\PostSitemapController;
use App\Http\Controllers\Sitemaps\SitemapController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::middleware('auth')->group(function () {
    Route::controller(BugController::class)->group(function () {
        Route::get('bugs', 'index')
            ->name('bugs.posts.index');

        Route::get('bugs/create', 'create')
            ->name('bugs.posts.create');
    });

    Route::get('favorites', [FavoritesController::class, 'index'])
        ->name(RouteNameEnum::FavoritesIndex);

    Route::get('preferences/{user}', [PreferencesController::class, 'edit'])
        ->name('preferences.edit');

    Route::controller(MeFiMailController::class)->group(function () {
        Route::get('mefi-mail', 'index')
            ->name('mefi.mail.index');

        Route::get('mefi-mail/create', 'create')
            ->name('mefi.mail.create');

        Route::get('mefi-mail/{mail}', 'show')
            ->name('mefi.mail.show');
    });

    Route::get('my-comments', [MyCommentsController::class, 'index'])
        ->name(RouteNameEnum::MetaFilterMyCommentsIndex);

    Route::get('my-favorites', [MyFavoritesController::class, 'index'])
        ->name(RouteNameEnum::MetaFilterMyFavoritesIndex);

    Route::get('my-mefi', [MyMeFiController::class, 'index'])
        ->name(RouteNameEnum::MetaFilterMyMeFiIndex);

    Route::controller(MyPostController::class)->group(function () {
        Route::get('my-posts', 'index')
            ->name(RouteNameEnum::MetaFilterMyPostsIndex);
    });

    Route::controller(MemberController::class)->group(function () {
        Route::get('members', [MemberController::class, 'edit'])
            ->name('members.edit');

        Route::patch('members', [MemberController::class, 'update'])
            ->name('members.update');

        Route::delete('members', [MemberController::class, 'delete'])
            ->name('members.delete');
    });
});

Route::get('archives/{year?}/{month?}/{day?}', [ArchivesController::class, 'index'])
    ->name('metafilter.archives.index');

Route::get('contact', [ContactMessageController::class, 'create'])
    ->middleware(ProtectAgainstSpam::class)
    ->name('contact.create');

Route::get('funding', [FundingController::class, 'index'])
    ->name('metafilter.funding.index');

Route::get('members', [MemberController::class, 'index'])
    ->name('members.index');

Route::get('members/{user:id}', [MemberController::class, 'show'])
    ->name('members.show');

Route::get('popular-posts', [PopularPostController::class, 'index'])
    ->name('metafilter.popular-posts.index');

Route::get('random', [RandomPostController::class, 'show'])
    ->name('metafilter.random-post.show');

Route::get('recent-activity', [RecentActivityController::class, 'show'])
    ->name('metafilter.recent-activity.show');

Route::get('recent-comments', [RecentCommentsController::class, 'index'])
    ->name('metafilter.recent-comments.index');

Route::get('sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap.index');

Route::get('sitemap/posts.xml', [PostSitemapController::class, 'index'])
    ->name('sitemap.posts.index');

Route::get('sitemap/posts/{letter}.xml', [PostSitemapController::class, 'show'])
    ->name('sitemap.posts.show');

Route::get('tags', [TagController::class, 'index'])
    ->name('metafilter.tags.index');

Route::controller(PostController::class)->group(function () {
    Route::get('', 'index')
        ->name('metafilter.posts.index');

    Route::get('{post}/{slug?}', 'show')
        ->whereNumber('post')
        ->name('metafilter.posts.show');

    Route::middleware('auth')->group(function () {
        Route::get('create', 'create')
            ->name('metafilter.posts.create');

        Route::post('store', 'store')
            ->name('metafilter.posts.store');

        Route::get('preview/{post}', 'preview')
            ->name('metafilter.posts.preview');

        Route::get('edit/{post}', 'edit')
            ->name('metafilter.posts.edit');

        Route::post('update', 'update')
            ->name('metafilter.posts.update');
    });
});
