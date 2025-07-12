<?php

declare(strict_types=1);

namespace App\Livewire\Posts;

use App\Dtos\PostDto;
use App\Enums\PostStateEnum;
use App\Http\Requests\Post\StorePostRequest;
use App\Services\PostService;
use App\Traits\LoggingTrait;
use App\Traits\SubsiteTrait;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class PostFormComponent extends Component
{
    use LoggingTrait;
    use SubsiteTrait;

    public string $title = '';
    public string $body = '';
    public string $more_inside = '';
    public string $tags = '';

    #[Locked]
    public int $subsiteId = 0;

    #[Locked]
    public int $userId = 0;

    private PostService $postService;
    public function boot(PostService $postService): void
    {
        $this->postService = $postService;
    }

    public function mount(): void
    {
        $this->subsiteId = $this->getSubsiteFromUrl()->id;
        $this->userId = auth()->id();
    }

    protected function rules(): array
    {
        return (new StorePostRequest())->rules();
    }

    public function render(): View
    {
        return view('livewire.posts.post-form-component');
    }

    public function store(): void
    {
        $this->validate();

        $dto = new PostDto(
            title: $this->title,
            body: $this->body,
            more_inside: $this->more_inside,
            tags: $this->tags,
            user_id: $this->userId,
            subsite_id: $this->subsiteId,
            state: PostStateEnum::Draft->value,
            published_at: now()->toDateTimeString(),
            is_published: true,
        );

        $post = $this->postService->store($dto);

        // TODO: Needs to preview first instead of just saving
        if ($post) {
            $this->logInfo('Post created');

            session()->flash('message', 'Post created');

            $slug = $post->slug;

            $this->redirect(route('metafilter.posts.show', ['post' => $post, 'slug' => $post->slug]));
        } else {
            $this->logError('Post creation failed');

            session()->flash('error', 'Post creation failed');
        }
    }
}
