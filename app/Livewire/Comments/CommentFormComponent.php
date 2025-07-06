<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Enums\LivewireEventEnum;
use App\Enums\ModerationTypeEnum;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Traits\LoggingTrait;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class CommentFormComponent extends Component
{
    use LoggingTrait;

    // Props
    #[Locked]
    public int $postId;

    #[Locked]
    public ?int $commentId;

    #[Locked]
    public ?int $parentId;

    #[Locked]
    public string $idSuffix;

    // Form data
    public string $body = '';
    public ?ModerationTypeEnum $moderationType = null;
    public string $message = '';

    // State
    public bool $isEditing;
    public bool $isReplying;
    public bool $isModerating;

    // Data not persisted in the client-side snapshot
    protected ?Comment $comment;

    #[Computed]
    public function isAdding(): bool
    {
        return !$this->isEditing && !$this->isReplying && !$this->isModerating;
    }

    #[Computed]
    public function isBodyEditable(): bool
    {
        return $this->isAdding || $this->isEditing ||
            ($this->isModerating && $this->moderationType === ModerationTypeEnum::Edit);
    }

    #[Computed]
    public function bodyEditorId(): string
    {
        return 'body-editor-' . ($this->commentId ?? 'new');
    }

    #[Computed]
    public function messageEditorId(): string
    {
        return 'message-editor-' . ($this->commentId ?? 'new');
    }

    public function mount(
        ?int $postId = null,
        ?int $commentId = null,
        ?int $parentId = null,
        ?Comment $comment = null,
        ?string $moderationType = null,
        bool $isEditing = false,
        bool $isReplying = false,
        bool $isModerating = false,
    ): void {
        $this->idSuffix = uniqid();

        // The implied alternative when not editing, replying or moderating is
        // that we are adding a new comment.
        $this->isEditing = $isEditing;
        $this->isReplying = $isReplying;
        $this->isModerating = $isModerating;

        $postId ??= $comment?->post_id;
        $commentId ??= $comment?->id;
        $parentId ??= ($isReplying || $isModerating ? $commentId : $comment?->parent_id);

        $this->postId = $postId;
        $this->commentId = $commentId;
        $this->parentId = $parentId;
        $this->comment = $comment;
        $this->moderationType = ModerationTypeEnum::tryFrom($moderationType ?? '');

        $this->body = $comment?->body ?? '';
    }

    public function render(): View
    {
        $isAdding = !$this->isEditing && !$this->isReplying && !$this->isModerating;
        $isBodyEditable = $isAdding || $this->isEditing ||
            ($this->isModerating && $this->moderationType === ModerationTypeEnum::Edit);
        $data = [
            'bodyLabel' => trans('Comment'),
            'messageLabel' => trans('Moderation message'),
            'buttonText' => trans('Add Comment'),
            'isAdding' => $isAdding,
            'isBodyEditable' => $isBodyEditable,
            'bodyEditorId' => $this->bodyEditorId,
            'messageEditorId' => $this->messageEditorId,
        ];

        if ($this->isModerating) {
            $data['bodyLabel'] = trans('Original comment');
            $data['buttonText'] = trans(match ($this->moderationType) {
                ModerationTypeEnum::Edit => 'Edit comment',
                ModerationTypeEnum::Remove => 'Remove comment',
                ModerationTypeEnum::Replace => 'Replace comment',
                ModerationTypeEnum::Wrap => 'Wrap comment',
                ModerationTypeEnum::Blur => 'Blur comment',
                default => 'Moderate',
            });
        } elseif ($this->isReplying) {
            $data['bodyLabel'] = trans('Reply');
            $data['buttonText'] = trans('Reply');
        } elseif ($this->isEditing) {
            $data['buttonText'] = trans('Edit comment');
        }

        return view('livewire.comments.comment-form-component', $data);
    }

    protected function rules(): array
    {
        return (new StoreCommentRequest())->rules();
    }

    public function submit(): void
    {
        $this->validate();

        if ($this->isModerating) {
            $this->moderate();
        } elseif ($this->isEditing) {
            $this->update();
        } else {
            $this->store();
        }
    }

    protected function clearEditors(): void
    {
        $this->reset('body');
        $this->reset('message');
        $this->dispatch('editor:clear', editorId: $this->bodyEditorId);
        $this->dispatch('editor:clear', editorId: $this->messageEditorId);
    }

    public function moderate(): void
    {
        try {
            // If moderator has edited the comment, save the new body.
            if ($this->moderationType === ModerationTypeEnum::Edit) {
                $comment = Comment::find($this->commentId ?? 0);

                if ($comment->body !== $this->body) {
                    $comment->body = $this->body;
                    $comment->save();
                    $this->dispatch(LivewireEventEnum::CommentUpdated->value, id: $comment->id, parentId: $comment->parent_id);
                }
            }

            // Create the moderation comment as a child of the original comment.
            $comment = new Comment(
                [
                    'post_id' => $this->postId,
                    'parent_id' => $this->parentId,
                    'user_id' => auth()->id() ?? null,
                    'moderation_type' => $this->moderationType,
                    'body' => $this->message,
                ],
            );

            $comment->save();

            $this->dispatch(LivewireEventEnum::CommentStored->value, id: $comment->id, parentId: $comment->parent_id);

            $this->clearEditors();
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    public function store(): void
    {
        try {
            $comment = new Comment(
                [
                    'body' => $this->body,
                    'post_id' => $this->postId,
                    'user_id' => auth()->id() ?? null,
                    'parent_id' => $this->parentId,
                ],
            );

            $comment->save();

            $this->dispatch(LivewireEventEnum::CommentStored->value, id: $comment->id, parentId: $comment->parent_id);

            $this->clearEditors();

            $this->message = trans('Comment created.');
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    public function update(): void
    {
        try {
            $comment = Comment::find($this->commentId ?? 0);
            $comment->body = $this->body;
            $comment->save();

            $this->dispatch(LivewireEventEnum::CommentUpdated->value, id: $comment->id, parentId: $comment->parent_id);

            $this->clearEditors();

            $this->message = trans('Comment updated.');
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }
}
