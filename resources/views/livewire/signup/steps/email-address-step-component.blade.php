<form wire:submit="submit">
    @include('forms.partials.validation-summary')
    @include('forms.partials.csrf-token')

    <fieldset class="required-fields">
        <x-forms.input
            name="email"
            type="text"
            note="We won&rsquo;t spam you, and we&rsquo;ll <strong>never</strong> give away your address to anyone. This address is hidden from other members and the public."
            label="{{ trans('Next, enter your email address') }}"
        />
    </fieldset>

    <div class="previous-next">
        <button type="button" class="button primary-button previous" wire:click="previousStep">
            {{ trans('Previous') }}
        </button>

        <button type="submit" class="button primary-button next">
            {{ trans('Next') }}
        </button>
    </div>
</form>
