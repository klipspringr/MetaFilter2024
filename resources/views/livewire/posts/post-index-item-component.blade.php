<article class="post" wire:key="post-{{ $post->id }}">
    @include('posts.partials.post-header', [
    ])

    {!! $post->body !!}

    @include('posts.partials.post-index-footer')
</article>
