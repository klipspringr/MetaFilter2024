<form
    wire:submit.prevent="submit"
    @if ($isEditing === true)
        id="reply-to-comment-form-{{ $comment->id }}"
    @endif
    @if ($isReplying === true)
        id="edit-comment-form-{{ $comment->id }}"
    @endif
>
    @include('forms.partials.validation-summary')
    @include('livewire.common.partials.posting-as')

    <fieldset>
        <div wire:ignore>
            <label for="body" class="sr-only">
                {{ trans('Comment') }}
            </label>

            <textarea wire:model="text" name="text" id="text" class="comment-textarea"></textarea>

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

{{--
@livewire('wysiwyg-editor', ['editorId' => 'editor1', 'content' => 'Initial content 1'])

<input wire:model="content" type="text">

<small class="smaller">
    <x-icons.icon-component filename="markdown-fill" />
    <a href="https://www.markdownguide.org/basic-syntax/"
       class="text-link"
       target="_blank">
        {{ trans('Styling with Markdown is supported') }}
    </a>
    <x-icons.icon-component filename="box-arrow-up-right" alt-text="Opens in new tab or window" />
</small>
--}}
