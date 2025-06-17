<?php

declare(strict_types=1);

namespace App\Enums;

enum LivewireEventEnum: string
{
    case CommentDeleted = 'comment-deleted';
    case CommentFlagDeleted = 'comment-flag-deleted';
    case CommentFlagged = 'comment-flagged';
    case CommentStored = 'comment-stored';
    case CommentUpdated = 'comment-updated';
    case EditorUpdated = 'editor-updated';
    case EscapeKeyClicked = 'escape-key-clicked';
    case FavoriteDeleted = 'favorite-deleted';
    case FavoriteStored = 'favorite-stored';
    case FlagStored = 'flag-stored';
    case FlagDeleted = 'flag-deleted';
    case HideFlagCommentForm = 'hide-flag-comment-form';
    case PostDeleted = 'post-deleted';
    case PostFlagDeleted = 'post-flag-deleted';
    case PostFlagged = 'post-flagged';
    case PostStored = 'post-stored';
    case PostUpdated = 'post-updated';
    case ToggleFlagCommentForm = 'toggle-flag-comment-form';
    case ToggleFlagPostForm = 'toggle-flag-post-form';
    case WatchingStarted = 'watching-started';
    case WatchingStopped = 'watching-stopped';
}
