<div class="notification is-info">
    <button popovertarget="{{ $popoverId }}" popovertargetaction="toggle">
        {{ trans($buttonText) }}
    </button>

    <div id="{{ $popoverId }}" popover>
        <x-icons.icon-component filename="info-circle-fill" />

        @if (!is_null($routeName))
            <a href="{{ route($routeName) }}">
                {{ trans($popoverText) }}
            </a>
        @else
            {{ trans($popoverText) }}
        @endif
    </div>
</div>
