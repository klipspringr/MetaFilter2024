<details class="moderator-hidden" :open="$wire.isOpen">
    <summary class="moderator-message" wire:click.stop.prevent="$js.toggleOpen">
        <section class="moderator-content">
            @if (!empty(trim($moderatorComment->body)))
                {!! $moderatorComment->body !!}
            @else
                {{ trans('This comment has been hidden.') }}
            @endif
        </section>

        <footer class="comment-footer">
            <div class="comment-metadata">
                <x-members.profile-link-component :user="$moderatorComment->user"/>

                @include('comments.partials.comment-timestamp', [
                    'comment' => $moderatorComment,
                ])
            </div>

            @moderator
                <div class="button-group">
                    @include('livewire.comments.partials.toggle-moderating-button', [
                        'commentId' => $comment->id,
                    ])
                </div>
            @endmoderator
        </footer>
        
        <footer class="comment-controls">
            <span class="show-comment">
                {{ trans('Show comment')}}
            </span>
            <span class="hide-comment">
                {{ trans('Hide comment')}}
            </span>
        </footer>
    </summary>

    @if ($isInitiallyBlurred)
        <livewire:comments.comment-blur
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
        />
    @else
        <livewire:comments.comment-content
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
        />
    @endif
</details>

@script
<script>
    $js('toggleOpen', () => {
        $wire.isOpen = !$wire.isOpen;
    });
</script>
@endscript