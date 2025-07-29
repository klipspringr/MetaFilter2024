<small class="posting-as">
    {{ trans('posting as') }}
    <x-members.profile-link-component :user="auth()->user()" />
</small>
