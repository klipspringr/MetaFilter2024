<form wire:submit="save">
    reset password form
    <x-forms.input
        name="email"
        type="email"
        autocomplete="true"
        text="{{ __('Email Address') }}"/>

    <x-forms.input
        name="password"
        type="password"
        text="{{ __('Password') }}"/>

    <x-forms.input
        name="password_confirmation"
        type="password"
        text="{{ __('Confirm Password') }}"/>

    <x-forms.button>
        {{ __('Reset Password') }}
    </x-forms.button>
</form>
