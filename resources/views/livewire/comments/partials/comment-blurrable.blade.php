<div class="blur-container"
    x-data="{ isBlurred: $wire.entangle('isBlurred') }"
    :class="{ 'blurred': isBlurred }">
    <div class="blur-content">
        @include('livewire.comments.partials.comment-content')
    </div>
    <div class="blur-overlay" x-show="isBlurred" @click="isBlurred = false">
        @if (!empty($blurMessage))
            {!! $blurMessage !!}
        @endif
    </div>
</div>
