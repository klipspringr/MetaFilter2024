<footer class="post-footer post-index-footer">
    <address>
        <x-members.profile-link-component :user="$post->user" />
    </address>

    at <x-dates.formatted-date-time-component :date="$post->created_at" format="g:i a" />

    <a class="button footer-button"
        href="{{ $post->present()->url }}#comments"
        title="{{ trans('Comments') }}">
        <x-icons.icon-component filename="chat" />
        {{ $post->commentsCount() }}
    </a>
</footer>
