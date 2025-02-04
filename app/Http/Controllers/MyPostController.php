<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;
use App\Services\PostService;
use App\Traits\PostTrait;
use Illuminate\Contracts\View\View;

final class MyPostController extends BaseController
{
    use PostTrait;

    public function __construct(
        protected PostRepositoryInterface $postRepository,
        protected PostService $postService,
    ) {
        parent::__construct();
    }

    public function index(): View
    {
        return view('my-posts.index', [
            'posts' => $this->getNewPostText(),
        ]);
    }

    public function show(Post $post): View
    {
        return view('posts.show', [
            'title' => $post->title,
            'post' => $post,
            'next' => $post->next(),
            'previous' => $post->previous(),
        ]);
    }

    public function create(): View
    {
        return view('my-posts.create', [
            'title' => $this->getNewPostText(),
        ]);
    }

    public function edit(Post $post): View
    {
        return view('my-posts.edit', [
            'title' => trans('Edit Post'),
            'post' => compact($post),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): void
    {
        $updated = $this->postService->update($request->validated());
    }

    public function delete(Post $post): void
    {
        $deleted = $this->postService->delete($post);
    }
}
