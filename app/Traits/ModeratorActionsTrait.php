<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\ModerationTypeEnum;
use App\Models\Comment;
use Illuminate\Support\Collection;

/**
 * Helper methods for comments that are moderator actions applied to a parent comment.
 */
trait ModeratorActionsTrait
{
    /**
     * Finds the last appearance-modifying comment.
     *
     * The effect of Reset is to override any previous appearance-modifying comment
     * and restore the default appearance for the original comment.
     */
    protected function findLastAppearanceComment(?Collection $comments): ?Comment
    {
        $appearanceComment = $comments?->last(
            fn($comment) => $comment->moderation_type !== null && match ($comment->moderation_type) {
                ModerationTypeEnum::Remove, ModerationTypeEnum::Replace, ModerationTypeEnum::Wrap, ModerationTypeEnum::Restore => true,
                default => false,
            },
        );

        // If the last appearance-modifying comment is a Restore, return null
        if ($appearanceComment && $appearanceComment->moderation_type === ModerationTypeEnum::Restore) {
            return null;
        }

        return $appearanceComment;
    }

    /**
     * Finds the most recent moderator blur comment.
     *
     * Blurring of comments can coexist with wrapping, but not with removal or replacement.
     * It is also reset by a later Restore comment.
     */
    protected function findLastBlurComment(?Collection $comments): ?Comment
    {
        $blurComment = $comments?->last(
            fn($comment) => $comment->moderation_type !== null && match ($comment->moderation_type) {
                ModerationTypeEnum::Blur, ModerationTypeEnum::Remove, ModerationTypeEnum::Replace, ModerationTypeEnum::Restore => true,
                default => false,
            },
        );

        // If the last blur-modifying comment is not a Blur, return null
        if ($blurComment && $blurComment->moderation_type !== ModerationTypeEnum::Blur) {
            return null;
        }

        return $blurComment;
    }

    /**
     * Filters the child comments to only include moderator actions that should be displayed.
     *
     * Moderator action visibility depends on whether we are currently moderating the comment or not.
     */
    protected function filterModeratorActions(Collection $childComments, bool $isModerating = false): Collection
    {
        return $childComments->filter(fn($comment) => $comment->moderation_type !== null && match ($comment->moderation_type) {
            ModerationTypeEnum::Comment, ModerationTypeEnum::Edit => true,
            ModerationTypeEnum::Blur, ModerationTypeEnum::Remove, ModerationTypeEnum::Replace, ModerationTypeEnum::Wrap, ModerationTypeEnum::Restore => $isModerating,
            default => false,
        });
    }
}
