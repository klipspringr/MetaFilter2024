@auth
    <button
        x-data="{ isFlagLoading: $wire.entangle('isFlagLoading') }"
        class="button footer-button"
        :class="{'loading': isFlagLoading}"
        :disabled="isFlagLoading"
        wire:key="toggle-flagging-button-{{ $comment->id }}"
        wire:click.prevent="$js.toggleFlagging"
        aria-controls="flag-comment-form-{{ $comment->id }}"
        aria-expanded="{{ $this->isFlagging ? 'true' : 'false' }}"
        title="{{ $userFlagged ? trans('Remove flag') : trans('Flag this comment') }}"
        @if ($authorizedUserId === null)
            disabled
        @endif
    >
        <x-icons.icon-component filename="{{ $userFlagged ? 'flag-fill' : 'flag' }}" />
        <x-icons.icon-component class="loading-icon" filename="bars-rotate-fade" />
        {{ $flagCount }}
    </button>
@endauth

@guest
    <button
        class="button footer-button"
        wire:key="toggle-flagging-button-{{ $comment->id }}"
        disabled>
        <x-icons.icon-component filename="flag" />
        {{ $flagCount }}
    </button>
@endguest

@script
<script>
    $js('toggleFlagging', () => {
        $wire.isFlagLoading = true;
        $wire.toggleFlagging();
    });
</script>
@endscript