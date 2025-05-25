<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Livewire\Forms\CommentForm;
use App\Models\Comment;
use Livewire\Component;

final class CommentEdit extends Component
{
    public Comment $comment;
    public CommentForm $form;

    public function mount(Comment $comment): void
    {
        $this->setComment($comment);
    }

    public function setComment(Comment $comment): void
    {
        $this->form->body = $comment->body;
        $this->form->postId = $comment->post_id;
        $this->form->parentId = $comment->parent_id;
    }
}
