@if ($isEditing === true)
    <livewire:comments.comment-form-component
        wire:key="edit-comment-{{ $comment->id }}"
        :comment="$comment"
        is-editing="true"
        button-text="{{ trans('Update') }}"
    />
@endif

@if ($isFlagging === true)
    <livewire:flags.flag-component
        wire:key="flagging-comment-{{ $comment->id }}"
        :comment-id="$comment->id"
        :model="$comment"
        @comment-flagged="addUserFlag($event.detail.id)"
        @comment-flag-cancelled="stopFlagging()"
        @comment-flag-deleted="removeUserFlag($event.detail.id)"
    />
@endif

@if ($isReplying === true)
    <livewire:comments.comment-form-component
        wire:key="reply-to-comment-{{ $comment->id }}"
        :comment="$comment"
        is-replying="true"
    />
@endif

@if ($isModerating === true)
    <livewire:comments.comment-form-component
        wire:key="moderate-comment-{{ $comment->id }}"
        :comment="$comment"
        is-moderating="true"
    />
@endif
