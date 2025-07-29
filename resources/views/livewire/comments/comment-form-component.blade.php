<form
    class="comment-form"
    wire:submit.prevent="submit"
    x-data="{
        isBodyEditable: {{ json_encode($this->isBodyEditable) }},
        isEditing: {{ json_encode($this->isEditing) }},
        isModerating: {{ json_encode($this->isModerating) }},
    }"
    @if ($isEditing === true)
        id="edit-comment-form-{{ $commentId }}"
    @elseif ($isReplying === true)
        id="reply-to-comment-form-{{ $commentId }}"
    @endif
>
    @include('forms.partials.csrf-token')
    @include('forms.partials.validation-summary')
    @include('livewire.common.partials.posting-as')

    <fieldset>
        @if ($isModerating)
        <div class="form-group">
            <label for="moderation-type-{{ $idSuffix }}" class="form-label">{{ trans('Moderation Type') }}</label>
            <select 
                wire:model.live="moderationType"
                id="moderation-type-{{ $idSuffix }}" 
                class="form-select">
                <option value="">{{ trans('Select moderation action...') }}</option>
                <option value="comment">{{ trans('Comment') }}</option>
                <option value="edit">{{ trans('Edit') }}</option>
                <option value="blur">{{ trans('Blur') }}</option>
                <option value="wrap">{{ trans('Wrap') }}</option>
                <option value="replace">{{ trans('Replace') }}</option>
                <option value="remove">{{ trans('Remove') }}</option>
                <option value="restore">{{ trans('Restore') }}</option>
            </select>
        </div>
        @endif

        <div class="form-group" x-show="isBodyEditable || (isModerating && $wire.moderationType === 'edit')">
            <label for="{{ $this->bodyEditorId }}">
                {{ trans($bodyLabel) }}
            </label>

            @if ($isBodyEditable)
            <livewire:wysiwyg.wysiwyg-component
                wire:key="body-editor"
                editor-id="{{ $this->bodyEditorId }}"
                name="body"
                wire:model="body" />
            @else
                <div class="loading-indicator">
                    <x-icons.icon-component class="loading-icon" filename="bars-rotate-fade" />
                </div>
            @endif
        </div>

        @if ($isModerating)
        <div class="form-group">
            <label for="{{ $this->messageEditorId }}">
                {{ $messageLabel }}
            </label>

            <livewire:wysiwyg.wysiwyg-component
                wire:key="message-editor"
                editor-id="{{ $this->messageEditorId }}"
                name="message"
                wire:model="message" />
        </div>
        @endif

        <div class="level">
            @if($isEditing === true || $isReplying === true || $isModerating === true)
                <button
                    type="button"
                    class="button secondary-button"
                    wire:click="$parent.closeForm({{ $commentId }})">
                    {{ trans('Cancel') }}
                </button>
            @endif

            <button
                type="submit"
                class="button primary-button"
                :disabled="isModerating && !$wire.moderationType">
                {{ $buttonText }}
            </button>
        </div>
    </fieldset>
</form>
