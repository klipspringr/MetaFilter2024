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

        @auth
            <livewire:bookmarks.bookmark-component
                wire:key="bookmark-component-{{ $comment->id }}"
                :model="$comment"
            />

            <livewire:favorites.favorite-component
                wire:key="favorite-component-{{ $comment->id }}"
                :model="$comment"
            />

            @if ($comment->user_id === auth()->id())
                @include('livewire.comments.partials.toggle-editing-button')
            @endif

            @include('livewire.comments.partials.toggle-replying-button')

            @include('livewire.comments.partials.toggle-moderating-button')
        @endauth

        @include('livewire.comments.partials.toggle-flagging-button')
    </footer>
</div>
