<?php

declare(strict_types=1);

namespace App\Livewire\Wizards;

use App\Dtos\PostDto;
use App\Enums\NotificationMessageEnum;
use App\Enums\NotificationTypeEnum;
use App\Enums\PostStateEnum;
use App\Http\Requests\Post\StorePostRequest;
use App\Models\Post;
use App\Services\PostService;
use App\Traits\DateAndTimeTrait;
use App\Traits\LoggingTrait;
use App\Traits\PostTrait;
use App\Traits\RedirectTrait;
use App\Traits\SubsiteTrait;
use Illuminate\Contracts\View\View;
use Throwable;

final class PostWizardComponent extends BaseWizardComponent
{
    use DateAndTimeTrait;
    use LoggingTrait;
    use PostTrait;
    use RedirectTrait;
    use SubsiteTrait;

    public array $steps = [
        'Write Post',
        'Add Tags',
        'Preview',
    ];

    public string $title = '';
    public string $body = '';
    public string $more_inside = '';
    public string $state = '';
    public ?string $published_at = null;
    public bool $is_published = false;
    public int $userId = 0;

    public Post $post;
    public int $subsiteId;

    private PostService $postService;

    public function boot(PostService $postService): void
    {
        $this->postService = $postService;
        $this->subsiteId = $this->getSubsiteFromUrl()->id;
        $this->userId = auth()->id();
    }

    public function render(): View
    {
        return view('livewire.wizards.posts.post-wizard-component');
    }

    public function rules(): array
    {
        return (new StorePostRequest())->rules();
    }

    // Step 1
    public function saveAsPending(): void
    {
        $this->validate();

        try {
            $stored = $this->storePost();

            $this->post = $stored;
            $this->currentStep = 2;
        } catch (Throwable $exception) {
            $this->logError($exception, 'Error saving post as pending');
        }
    }

    // Step 2
    public function submitTags(): void
    {
        $this->currentStep = 3;
    }

    public function saveAsDraft(): void
    {
        \Log::debug('saveAsDraft');
        $state = PostStateEnum::Draft->value;

        $data = [
            'state' => $state,
        ];

        try {
            $this->postService->update($this->post->id, $data);
        } catch (Throwable $exception) {
            $this->logError($exception, 'Error updating post to draft');
        }

        $url = $this->getPostShowUrl($this->post);
        $type = NotificationTypeEnum::Success->value;
        $message = NotificationMessageEnum::PostDraftSuccess->value;

        $this->redirectToUrlWithNotification($url, $type, $message);
    }

    public function publishPost(): void
    {
        \Log::debug('publishPost');

        try {
            $publishedAt = $this->getFormattedInsertDateTime(now());

            $data = [
                'published_at' => $publishedAt,
                'is_published' => true,
            ];

            $this->postService->update($this->post->id, $data);
        } catch (Throwable $exception) {
            $this->logError($exception, 'Error publishing post');
        }

        $url = $this->getPostShowUrl($this->post);
        $type = NotificationTypeEnum::Success->value;
        $message = NotificationMessageEnum::PostPublishSuccess->value;

        $this->redirectToUrlWithNotification($url, $type, $message);
    }

    private function storePost(): ?Post
    {
        try {
            $state = PostStateEnum::Pending->value;

            $dto = new PostDto(
                title: $this->title,
                body: $this->body,
                more_inside: $this->more_inside,
                tags: null, // Tags will be handled in a later step
                user_id: $this->userId,
                subsite_id: $this->subsiteId,
                state: $state,
                published_at: null,
                is_published: false,
            );

            return $this->postService->store($dto);
        } catch (Throwable $exception) {
            $this->logError($exception, 'Error storing post');

            return null;
        }
    }
}
