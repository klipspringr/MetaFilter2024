<div wire:ignore>
    @if($label)
    <label for="{{ $editorId}}">{{ $label }}</label>
    @endif

    <textarea
        name="{{ $editorId }}"
        class="wysiwyg"
        id="{{ $editorId }}"
        data-editor-id="{{ $editorId }}"
        wire:model.lazy="content">
    </textarea>
</div>
