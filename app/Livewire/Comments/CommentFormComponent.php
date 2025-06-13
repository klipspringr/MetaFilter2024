<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\LivewireEventEnum;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Traits\LoggingTrait;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

final class CommentFormComponent extends Component
{
    use LoggingTrait;

    public ?int $authorizedUserId;
    public string $buttonText = '';
    public ?Comment $comment;
    public bool $isEditing;
    public bool $isReplying;
    public string $body = '';
    public int $postId;
    public ?int $parentId = null;
    public ?string $message = null;

    public function mount(
        int $postId,
        ?Comment $comment,
        ?int $parentId = null,
        bool $isEditing = false,
        bool $isReplying = false,
    ): void {
        $this->authorizedUserId = auth()->id() ?? null;

        $this->isEditing = $isEditing;
        $this->isReplying = $isReplying;

        $this->postId = $postId;
        $this->parentId = $parentId;

        $this->comment = $comment ?? null;
        $this->body = $this->comment->body ?? '';
    }

    public function render(): View
    {
        return view('livewire.comments.comment-form-component');
    }

    protected function rules(): array
    {
        return (new StoreCommentRequest())->rules();
    }

    #[On(LivewireEventEnum::EditorUpdated->value)]
    public function saveEditorContent($editorId, $content): void
    {
        if ($editorId === 'comment-text') {
            $this->body = $content;
        }
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->isEditing === true) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function store(): void
    {
        try {
            $comment = new Comment(
                [
                    'body' => $this->body,
                    'post_id' => $this->postId,
                    'user_id' => $this->authorizedUserId,
                    'parent_id' => $this->parentId,
                ],
            );

            $comment->save();

            $this->dispatch(LivewireEventEnum::CommentStored->value);

            $this->message = trans('Comment created.');
        } catch (Exception $exception) {
            $this->logError($exception);
        }

        $this->reset('body');
    }

    public function update(): void
    {
        try {
            $comment = Comment::find($this->comment->id);
            $comment->body = $this->body;
            $comment->save();

            $this->dispatch(LivewireEventEnum::CommentUpdated->value);

            $this->message = trans('Comment updated.');
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }
}
