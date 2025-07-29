@php
use App\Enums\ModerationTypeEnum;
use App\Models\Comment;
@endphp

<section class="comments moderator-actions">
    @if ($isModerating)
    <h2>
        {{ trans('Moderation History') }}
    </h2>
    @endif

    @foreach ($comments as $comment)
        <livewire:comments.moderator-comment
            :key="$comment->id"
            :comment-id="$comment->id"
            :$comment
            :$state
        />
    @endforeach
</section>
