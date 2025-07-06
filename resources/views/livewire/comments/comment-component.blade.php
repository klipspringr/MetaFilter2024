@php
use App\Enums\ModerationTypeEnum;
@endphp

<article class="comment"
    data-comment-id="{{ $commentId }}"
    data-member-id="{{ $comment->user->id }}"
>
    @if ($moderationType === ModerationTypeEnum::Replace)
        <livewire:comments.comment-replacement
            wire:key="comment-replacement-{{ $replacedByCommentId ?? $commentId }}"
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
            @comment-form-state-changed="setState($event.detail.state)"
        />
    @elseif ($moderationType === ModerationTypeEnum::Wrap)
        <livewire:comments.comment-wrapper
            wire:key="comment-wrapper-{{ $wrappedByCommentId ?? $commentId }}"
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
            @comment-form-state-changed="setState($event.detail.state)"
        />
    @elseif ($isInitiallyBlurred)
        <livewire:comments.comment-blur
            wire:key="comment-blur-{{ $blurredByCommentId ?? $commentId }}"
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
            @comment-form-state-changed="setState($event.detail.state)"
        />
    @else
        <livewire:comments.comment-content
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
            @comment-form-state-changed="setState($event.detail.state)"
        />
    @endif

    @if ($isEditing === true)
        <livewire:comments.comment-form-component
            wire:key="edit-comment-{{ $commentId }}"
            :comment="$comment"
            is-editing="true"
            @comment-updated="closeForm()"
            @comment-stored="closeForm()"
        />
    @endif

    @if ($isFlagging === true)
        <livewire:flags.flag-component
            wire:key="flagging-comment-{{ $commentId }}"
            :comment-id="$comment->id"
            :model="$comment"
            @comment-flagged="addUserFlag($event.detail.id)"
            @comment-flag-cancelled="closeForm()"
            @comment-flag-deleted="removeUserFlag($event.detail.id)"
        />
    @endif

    @if ($isReplying === true)
        <livewire:comments.comment-form-component
            wire:key="reply-to-comment-{{ $commentId }}"
            :comment="$comment"
            is-replying="true"
            @comment-updated="closeForm()"
            @comment-stored="closeForm()"
        />
    @endif

    @if ($isModerating === true)
        <livewire:comments.comment-form-component
            wire:key="moderate-comment-{{ $commentId }}"
            :comment="$comment"
            is-moderating="true"
            @comment-updated="closeForm()"
            @comment-stored="closeForm()"
        />
    @endif
</article>

@script
<script>
    $js('toggleBlurred', () => {
        $wire.isBlurred = !$wire.isBlurred;
    });
    $js('toggleOpen', () => {
        $wire.isOpen = !$wire.isOpen;
    });
</script>
@endscript