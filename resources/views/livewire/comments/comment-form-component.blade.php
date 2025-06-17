<form
    wire:submit.prevent="submit"
    @if ($isEditing === true)
        id="edit-comment-form-{{ $comment->id }}"
    @elseif ($isReplying === true)
        id="reply-to-comment-form-{{ $comment->id }}"
    @endif
>
    @include('forms.partials.csrf-token')
    @include('forms.partials.validation-summary')
    @include('livewire.common.partials.posting-as')

    <fieldset>
        <div wire:ignore>
            <label for="{{ $this->editorId }}" class="sr-only">
                {{ trans('Comment') }}
            </label>

            <livewire:wysiwyg.wysiwyg-component
                    editor-id="{{ $this->editorId }}"
                    name="text"
                    content="{!! $comment->body !!}" />

            <div class="level">
                @if($isEditing === true || $isReplying === true)
                    <button
                        type="button"
                        class="button secondary-button"
                        wire:click="$parent.closeForm({{ $comment->id }})">
                        {{ trans('Cancel') }}
                    </button>
                @endif

                <button
                    type="submit"
                    class="button primary-button">
                    @if (!empty($buttonText))
                        {{ trans($buttonText) }}
                    @else
                        {{ trans('Add Comment') }}
                    @endif
                </button>
            </div>
        </div>
    </fieldset>
</form>
