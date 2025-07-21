<section class="comments">
    <h2 class="sr-only">
        {{ trans('Comments') }}
    </h2>

    @php
        // We will display moderator comments alongside the original comment,
        // if it is present. We can split the collection of comments on the
        // post into collections per moderated comment, with top-level comments
        // grouped under an ID of 0.
        $commentsByParentId = $comments->groupBy(fn ($comment) => $comment->parent_id ?? 0);
        $rootComments = $commentsByParentId->get(0) ?? [];
    @endphp
    @forelse ($rootComments as $comment)
        @php
            $childComments = $commentsByParentId->get($comment->id);
        @endphp

        <livewire:comments.comment-component
            :key="$comment->id"
            :comment-id="$comment->id"
            :comment="$comment"
            :child-comments="$childComments"
        />
    @empty
        @include('notifications.none-listed', [
            'records' => $recordsText,
        ])
    @endforelse

    <!-- div wire:poll.5s="getComments"></div -->
</section>
