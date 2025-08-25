<form
    action="{{ route('logout') }}"
    class="logout-form"
    method="POST"
>
    @include('forms.partials.csrf-token')

    <button type="submit" class="logout-button">
        <x-icons.icon-component filename="box-arrow-right" />
        {{ trans('Log Out') }}
    </button>
</form>
