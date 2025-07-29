@moderator
<button
    class="button footer-button"
    wire:click.stop="toggleModerating()"
    aria-controls="comment-moderation-form-{{ $commentId }}"
    aria-expanded="{{ json_encode($isModerating) }}">
    <x-icons.icon-component filename="stoplights-fill" />
    {{ trans('Moderate') }}
</button>
@endmoderator
