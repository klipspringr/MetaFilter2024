<?php

declare(strict_types=1);

namespace App\Livewire\Comments;

use App\Models\Comment;
use Illuminate\View\View;
use Livewire\Component;

final class ReplyToCommentComponent extends Component
{
    public Comment $comment;
    public string $replyFormId = '';
    public string $replyText = '';
    public string $selectedText = '';
    public bool $isVisible = false;

    protected array $rules = [
        'replyText' => [
            'required',
            'string',
            'min:3',
        ],
    ];

    public function mount(Comment $comment): void
    {
        $this->comment = $comment;
    }

    public function render(): View
    {
        return view('livewire.comments.reply-to-comment-component');
    }

    public function store(): void
    {
        $this->validate();

        (new Comment())->create([
            'content' => $this->replyText,
            'parent_id' => $this->comment->id,
        ]);

        $this->replyText = '';
        $this->selectedText = '';
        $this->isVisible = false;

        $this->dispatch('replyAdded');
    }
}
