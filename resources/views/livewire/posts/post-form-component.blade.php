<form wire:submit.prevent="store()">
    @include('forms.partials.csrf-token')
    @include('forms.partials.validation-summary')
    @include('livewire.common.partials.posting-as')

    <x-forms.input
        name="title"
        type="text"
        label="{{ trans('Title') }}" />

    <div wire:ignore>
        <livewire:wysiwyg.wysiwyg-component
            editor-id="post-body"
        />
    </div>

    <div wire:ignore>
        <livewire:wysiwyg.wysiwyg-component
            editor-id="more-inside"
            label="{{ trans('More Inside') }}"
        />
    </div>
    <x-forms.input
        name="tags"
        type="text"
        note="{!! trans('Combine and capitalize words, <strong>LikeThis</strong>') !!}"
        label="{{ trans('Tags') }}" />

    <x-forms.hidden
        name="user_id"
        value="{{ $userId }}" />

    <button type="submit" class="button primary-button">
        {{ trans('Preview') }}
    </button>
</form>