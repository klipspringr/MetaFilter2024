<div class="blur-container"
    x-data="{ isBlurred: $wire.entangle('isBlurred') }"
    :class="{ 'blurred': isBlurred }">
    <div class="blur-content">
        <livewire:comments.comment-content
            :comment-id="$commentId"
            :comment="$comment"
            :child-comments="$childComments"
            :$state
        />
    </div>
    <div class="blur-overlay" x-show="isBlurred" @click="isBlurred = false">
        @if (!empty($blurMessage))
            {!! $blurMessage !!}
        @endif
    </div>
</div>

@script
<script>
    $js('toggleBlurred', () => {
        $wire.isBlurred = !$wire.isBlurred;
    });
</script>
@endscript