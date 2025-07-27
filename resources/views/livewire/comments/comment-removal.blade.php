<div class="moderator-message moderator-removed">
    <section class="moderator-content">
        {!! $moderatorComment->body !!}
    </section>
    <footer class="comment-footer">
        <div class="comment-metadata">
            <x-members.profile-link-component :user="$moderatorComment->user"/>

            @include('comments.partials.comment-timestamp', [
                'comment' => $moderatorComment,
            ])
        </div>

        @auth
            @include('livewire.comments.partials.toggle-moderating-button')
        @endauth
    </footer>
</div>