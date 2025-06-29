<form class="flag-form" wire:submit.prevent="$js.store" wire:show="!formClosed">
    <fieldset>
        <legend>
            {{ $titleText }}
        </legend>

        @if ($userFlag === null)
        <small>
            <x-popovers.popover-component
                button-text="{{ trans('What does it mean to flag a comment or post?') }}"
                popover-text="{{ trans('What does it mean to flag a comment or post?') }}"
                show-info-icon="true"
            />
        </small>
        @endif

        @foreach ($flagReasons as $key => $reason)
            <label for="{{ $type }}-{{ $model->id }}-flag-reason-{{ $key }}" class="block radio-button-label">
                <input
                    id="{{ $type }}-{{ $model->id }}-flag-reason-{{ $key }}"
                    class="flag-reason-{{ $key }}"
                    type="radio"
                    name="selectedReason"
                    wire:model="selectedReason"
                    wire:click="showNoteField = {{ json_encode($this->isNoteVisibleForReason($reason)) }}"
                    value="{{ $reason }}">
                {{ $reason }}
            </label>
        @endforeach

        <div wire:show="showNoteField">
            <label for="{{ $type }}-{{ $model->id }}-flag-note" class="optional">
                {{ trans('Note') }}
            </label>

            <textarea
                id="{{ $type }}-{{ $model->id }}-flag-note"
                class="flag-note resize"
                name="note"
                wire:model="note"
                placeholder="{{ trans('Additional details (optional)') }}">
            </textarea>
        </div>
    </fieldset>

    <div class="level">
        @if ($userFlag !== null)
            <button
                type="button"
                class="button secondary-button"
                wire:click="$js.delete">
                {{ trans('Remove Flag') }}
            </button>
        @endif

        <button
            type="button"
            class="button secondary-button"
            wire:click="$js.cancel">
            {{ trans('Cancel') }}
        </button>

        <button
            type="submit"
            class="button primary-button">
            {{ $userFlag ? trans('Update Flag') : trans('Add Flag') }}
        </button>
    </div>
</form>

@script
<script>
    $js('store', () => {
        // Close form client-side and send a loading event so the flag button
        // can show the loading state until the backend work is done.
        $wire.formClosed = true;
        $dispatch('flag-loading', {id: $wire.modelId, type: $wire.type});
        $wire.store();
    });

    $js('delete', () => {
        $wire.formClosed = true;
        $dispatch('flag-loading', {id: $wire.modelId, type: $wire.type});
        $wire.delete();
    });

    $js('cancel', () => {
        $wire.formClosed = true;
        $dispatch('flag-loading', {id: $wire.modelId, type: $wire.type});
        $wire.cancel();
    });
</script>
@endscript