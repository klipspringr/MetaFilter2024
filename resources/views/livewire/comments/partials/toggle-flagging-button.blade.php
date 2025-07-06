@auth
    <button
        x-data="{ isFlagLoading: false }"
        class="button footer-button"
        :class="{'loading': isFlagLoading}"
        :disabled="isFlagLoading"
        wire:key="toggle-flagging-button-{{ $comment->id }}"
        wire:click.prevent="$js.toggleFlagging"
        aria-controls="flag-comment-form-{{ $comment->id }}"
        aria-expanded="{{ json_encode($isFlagging) }}"
        title="{{ $userFlagged ? trans('Remove flag') : trans('Flag this comment') }}"
    >
        <x-icons.icon-component filename="{{ $userFlagged ? 'flag-fill' : 'flag' }}" />
        <x-icons.icon-component class="loading-icon" filename="bars-rotate-fade" />
        {{ $flagCount }}
    </button>
@endauth

@guest
    <button
        x-data="{ isFlagLoading: false }"
        class="button footer-button"
        wire:key="toggle-flagging-button-{{ $comment->id }}"
        disabled>
        <x-icons.icon-component filename="flag" />
        {{ $flagCount }}
    </button>
@endguest

@script
<script>
    (() => {
        const commentId = $wire.commentId;
        const flagButtonKey = `toggle-flagging-button-${commentId}`;

        // Set the loading state of the flag button.
        function setFlagLoading(isLoading) {
            for (const button of $wire.el.querySelectorAll('button.button.footer-button')) {
                if (button.getAttribute('wire:key') === flagButtonKey) {
                    const $data = Alpine.$data(button);
                    $data.isFlagLoading = isLoading;
                    break;
                }
            }
        }

        // Define callback that invokes toggleFlagging via Livewire after
        // setting the loading state.
        $wire.$js('toggleFlagging', () => {
            setFlagLoading(true);
            $wire.toggleFlagging();
        });

        // We may be nested 1-3 levels deep in the comment component, hence
        // we have to loop to find it.
        let componentWire = $wire;
        const commentComponentName = 'comments.comment-component';

        while (componentWire && componentWire.__instance.name !== commentComponentName) {
            try {
                // Attempting to access $parent will throw an error if the parent is not found.
                componentWire = componentWire.$parent;
            } catch (error) {
                componentWire = null;
            }
        }

        // If we didn't find the comment component, we can't do anything.
        if (!componentWire) return false;

        componentWire.hook('morphed', () => {
            // The flag button should stop spinning when the comment component has morphed.
            setFlagLoading(false);
        });

        componentWire.on('flag-loading', () => {
            // The flag button should start spinning when the flag loading event is dispatched.
            setFlagLoading(true);
        });

        return true;
    })();
</script>
@endscript