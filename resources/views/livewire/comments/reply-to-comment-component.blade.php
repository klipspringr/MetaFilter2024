    <form>
        <textarea wire:model="replyText" placeholder="{{ trans('Write your reply') }}&hellip;" class="comment-textarea">

        </textarea>

        <div class="level">
            <button
                type="button"
                class="button secondary-button"
                wire:click="$parent.closeForm({{ $comment->id }})">
                {{ trans('Cancel') }}
            </button>

            <button
                type="submit"
                class="button primary-button"
                wire:click="store()">
                {{ trans(' Reply') }}
            </button>
        </div>
    </form>

