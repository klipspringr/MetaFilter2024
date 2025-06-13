<div>
    @if ($authorizedUserId === $comment->user_id)
        <button wire:click="toggleEdit" class="button">
            {{ $isEditing ? 'Cancel' : 'Edit' }}
        </button>

        @if ($isEditing)
            <form wire:submit.prevent="updateComment()">
                <textarea wire:model="content" class="comment-textarea"></textarea>

                @error('content')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror

                <div class="level">
                    <button type="button" class="button-link">
                        {{ trans('Cancel') }}
                    </button>

                    <button type="submit" class="button">
                        {{ trans('Update') }}
                    </button>
                </div>
            </form>
        @endif
    @endif
</div>
