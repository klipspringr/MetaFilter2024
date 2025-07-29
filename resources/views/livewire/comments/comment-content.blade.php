<div class="comment-container">
    <section class="comment-content">
        {!! $body !!}
    </section>

    <footer class="comment-footer">
        <div class="comment-metadata">
            <x-members.profile-link-component :user="$comment->user"/>

            @include('comments.partials.comment-timestamp', [
                'comment' => $comment,
            ])
        </div>

        <div class="button-group">
            @auth
                <livewire:bookmarks.bookmark-component
                    wire:key="bookmark-component-{{ $comment->id }}"
                    :model="$comment"
                />

                <livewire:favorites.favorite-component
                    wire:key="favorite-component-{{ $comment->id }}"
                    :model="$comment"
                />
            @endauth

            @include('livewire.comments.partials.toggle-flagging-button')

            @if ($this->isInitiallyBlurred)
                @include('livewire.comments.partials.toggle-blur-button')
            @endif
        </div>

        @auth
            <div class="button-group">
                @if ($comment->user_id === auth()->id())
                    @include('livewire.comments.partials.toggle-editing-button')
                @endif

                @include('livewire.comments.partials.toggle-replying-button')

                @include('livewire.comments.partials.toggle-moderating-button', [
                    'commentId' => $comment->id,
                ])
            </div>
        @endauth
    </footer>
</div>
