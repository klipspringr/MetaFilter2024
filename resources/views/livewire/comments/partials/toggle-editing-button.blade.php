<button
    class="button footer-button"
    wire:click.prevent="toggleEditing()"
    aria-controls="edit-comment-form-{{ $comment->id }}"
    aria-expanded="{{ json_encode($isEditing) }}">
    <x-icons.icon-component filename="pencil-square" />
    {{ trans('Edit') }}
</button>
