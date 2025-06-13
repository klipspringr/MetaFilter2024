<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Traits\AuthStatusTrait;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class EditCommentComponent extends Component
{
    use AuthStatusTrait;

    public Comment $comment;
    public int $authorizedUserId;
    public string $commentText;
    public bool $isEditing = false;
    protected CommentRepository $commentRepository;

    protected array $rules = [
        'commentText' => [
            'required',
            'string',
        ],
    ];

    public function mount(Comment $comment, CommentRepository $commentRepository): void
    {
        $this->authorizedUserId = $this->getAuthorizedUserId();

        $this->comment = $comment;
        $this->commentText = $comment->body;

        $this->commentRepository = $commentRepository;
    }

    public function render(): View
    {
        return view('livewire.comments.edit-comment-component');
    }

    public function toggleEdit(): void
    {
        if ($this->authorizedUserId !== $this->comment->user_id) {
            return;
        }

        $this->isEditing = !$this->isEditing;
    }

    public function updateComment(): void
    {
        if ($this->authorizedUserId !== $this->comment->user_id) {
            return;
        }

        $this->validate();

        $this->comment->update([
            'text' => $this->commentText,
        ]);

        $this->isEditing = false;

        session()->flash('message', trans('Comment updated successfully.'));
    }
}
