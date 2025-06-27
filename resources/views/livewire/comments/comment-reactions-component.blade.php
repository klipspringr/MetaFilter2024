<div>
    @foreach ($this->types as $type)
        {{-- TODO: Move types to a config array to include text like mark as a favorite and the icon --}}
        <button wire:click="toggleReaction('{{ $type }}')">
            {{ $type }} ({{ $reactions->where('type', '=', $type)->count() }}) + more
        </button>
    @endforeach
</div>
