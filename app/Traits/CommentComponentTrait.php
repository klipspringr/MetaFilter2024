<?php

declare(strict_types=1);

namespace App\Traits;

trait CommentComponentTrait
{
    public function toggleEditing(): void
    {
        if ($this->isEditing === true) {
            $this->stopEditing();
        } else {
            $this->startEditing();
        }
    }

    public function toggleFlagging(): void
    {
        if ($this->isFlagging === true) {
            $this->stopFlagging();
        } else {
            $this->startFlagging();
        }
    }

    public function toggleReplying(): void
    {
        if ($this->isReplying === true) {
            $this->stopReplying();
        } else {
            $this->startReplying();
        }
    }

    public function startEditing(): void
    {
        $this->isEditing = true;
        $this->stopFlagging();
        $this->stopReplying();
    }

    public function stopEditing(): void
    {
        $this->isEditing = false;
    }

    public function startFlagging(): void
    {
        $this->isFlagging = true;
        $this->isFlagLoading = false;
        $this->stopEditing();
        $this->stopReplying();
    }

    public function stopFlagging(): void
    {
        $this->isFlagging = false;
        $this->isFlagLoading = false;
    }

    public function startReplying(): void
    {
        $this->isReplying = true;
        $this->stopEditing();
        $this->stopFlagging();
    }

    public function stopReplying(): void
    {
        $this->isReplying = false;
    }
}
