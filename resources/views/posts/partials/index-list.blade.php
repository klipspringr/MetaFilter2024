@foreach ($dayPosts as $date => $posts)
    <h2>{{ $date }}</h2>

    @foreach ($posts as $post)
        <article class="post" wire:key="post-{{ $post->id }}">
            @include('posts.partials.post-header', [
            ])

            {{ $post->body }}

            @include('posts.partials.post-index-footer', [
                'post' => $post,
                'userId' => $post->user_id,
                'username' => $post->username,
                'commentsCount' => $post->comments_count,
            ])
        </article>
    @endforeach
@endforeach
