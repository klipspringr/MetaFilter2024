<div wire:ignore>
    @if($label)
        <label for="{{ $editorId}}">{{ $label }}</label>
    @endif

    <textarea
        name="{{ $name }}"
        class="wysiwyg"
        id="{{ $editorId }}"
        data-editor-id="{{ $editorId }}"
        data-editor-config="{{ json_encode($editorConfig) }}"
        wire:model.lazy="content">{!! $content !!}</textarea>
</div>
