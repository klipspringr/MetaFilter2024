<form class="has-steps" wire:submit.prevent="submitUsername()">
    @include('forms.partials.validation-summary')
    @include('forms.partials.csrf-token')

    <fieldset>
        <x-forms.input
            :name="'username'"
            :type="'text'"
            :note="'Your username will be displayed with your posts and comments and can&rsquo;t be changed.
                    Usernames may consist of English letters, numbers, and most punctuation.'"
            :label="'First, pick your username'"
            :autofocus="true"
            :required="true"
        />

        <x-forms.input
            :name="'name'"
            :type="'text'"
            :label="'What&rsquo;s your name?'"
            :autofocus="false"
            :required="false"
        />
    </fieldset>

    <div class="level">
        <x-forms.button
            type="submit"
            class="primary-button next-step">
            {{ trans('Next') }}
        </x-forms.button>
    </div>
</form>
