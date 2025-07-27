@php
use App\Enums\ModerationTypeEnum;
@endphp

<article class="comment @if ($isRemoved) moderator-removed @endif"
    data-comment-id="{{ $commentId }}"
    @if (!$isRemoved)
        data-member-id="{{ $comment->user->id }}"
    @endif
>
    @if ($moderationType === ModerationTypeEnum::Remove)
        @moderator
            <livewire:comments.comment-removal
                wire:key="comment-removal-{{ $appearanceCommentId ?? $commentId }}"
                :comment-id="$commentId"
                :comment="$comment"
                :child-comments="$childComments"
                :$state
                @comment-form-state-changed="setState($event.detail.state)"
            />
        @endmoderator
    @elseif ($moderationType === ModerationTypeEnum::Replace)
        <livewire:comments.comment-replacement
            wire:key="comment-replacement-{{ $appearanceCommentId ?? $commentId }}"
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
            @comment-form-state-changed="setState($event.detail.state)"
        />
    @elseif ($moderationType === ModerationTypeEnum::Wrap)
        <livewire:comments.comment-wrapper
            wire:key="comment-wrapper-{{ $appearanceCommentId ?? $commentId }}"
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
            @comment-form-state-changed="setState($event.detail.state)"
        />
    @else
        @if ($isInitiallyBlurred)
            <livewire:comments.comment-blur
                wire:key="comment-blur-{{ $blurCommentId ?? $commentId }}"
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
