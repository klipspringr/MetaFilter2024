<button
    class="button footer-button"
    wire:click.prevent="toggleReplying()"
    aria-controls="comment-reply-form-{{ $comment->id }}"
    aria-expanded="{{ json_encode($isReplying) }}">
    <x-icons.icon-component filename="reply-fill" />
    {{ trans('Reply') }}
</button>
