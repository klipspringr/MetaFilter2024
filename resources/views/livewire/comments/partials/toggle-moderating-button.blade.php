@moderator
<button
    class="button footer-button"
    wire:click.prevent="toggleModerating()"
    aria-controls="comment-moderation-form-{{ $comment->id }}"
    aria-expanded="{{ json_encode($isModerating) }}">
    <x-icons.icon-component filename="stoplights-fill" />
    {{ trans('Moderate') }}
</button>
@endmoderator
