<button
    class="button footer-button"
    type="button"
    title="{{ $titleText }}"
    @auth
        @if (isset($userFavorited) && $userFavorited === true)
            wire:click="removeFavorite()"
        @else
            wire:click="addFavorite()"
        @endif
    @endauth
 >
    <x-icons.icon-component filename="{{ $iconFilename }}" />
    {{ $favoriteCount }}
</button>
