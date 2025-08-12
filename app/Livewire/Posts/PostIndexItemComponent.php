<?php

declare(strict_types=1);

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Traits\AuthStatusTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class PostIndexItemComponent extends Component
{
    use AuthStatusTrait;

    public Post $post;
    public ?int $authorizedUserId;
    public int $commentCount = 0;

    public function mount(Post $post): void
    {
        $this->authorizedUserId = $this->getAuthorizedUserId();
        $this->post = $post;
        $this->commentCount = $post->commentsCount();
    }

    public function render(): View
    {
        return view('livewire.posts.post-index-item-component', [
            'authorizedUserId' => $this->authorizedUserId,
        ]);
    }
}
