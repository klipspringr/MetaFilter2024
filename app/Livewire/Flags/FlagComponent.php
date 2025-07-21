<?php

declare(strict_types=1);

namespace App\Livewire\Flags;

use App\Enums\LivewireEventEnum;
use App\Models\Comment;
use App\Models\Flag;
use App\Models\Post;
use App\Traits\AuthStatusTrait;
use App\Traits\LoggingTrait;
use App\Traits\TypeTrait;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Maize\Markable\Exceptions\InvalidMarkValueException;

final class FlagComponent extends Component
{
    use AuthStatusTrait;
    use LoggingTrait;
    use TypeTrait;

    private const string FLAG_WITH_NOTE = 'Flag with note';
    private const string MODEL_PATH = 'app\\models\\';

    public Comment|Post $model;
    public Flag|null $userFlag = null;

    #[Locked]
    public int $modelId;
    #[Locked]
    public string $type;
    #[Locked]
    public int $flagCount = 0;
    #[Locked]
    public string $iconFilename = 'flag';
    #[Locked]
    public string $titleText;
    #[Locked]
    public array $flagReasons = [];

    // Actual values we interact with
    public string $note = '';
    public string $selectedReason = '';
    public bool $showNoteField = false;
    public bool $formClosed = false;

    public function mount(
        Comment|Post $model,
    ): void {
        $configReasons = config('markable.allowed_values.flag', []);

        $this->flagReasons = is_array($configReasons) ? array_combine(
            array_map(fn($reason) => mb_strtolower(preg_replace('/[^\w]+/', '-', $reason)), $configReasons),
            $configReasons,
        ) : [];

        $this->formClosed = false;
        $this->model = $model;
        $this->modelId = $model->id;
        $this->type = $this->getType();
        $this->userFlag = $this->getUserFlag();
        $this->updateFlagData();
    }

    public function render(): View
    {
        return view('livewire.flags.flag-component');
    }

    public function isNoteVisibleForReason(string $reason): bool
    {
        return $reason === self::FLAG_WITH_NOTE;
    }

    public function getUserFlag(): Flag | null
    {
        return Flag::where([
            'user_id' => auth()->id(),
            'markable_id' => $this->model->getKey(),
            'markable_type' => $this->model->getMorphClass(),
        ])->first();
    }

    protected function updateFlagData(): void
    {
        $value = $this->userFlag?->value ?? '';
        $this->selectedReason = in_array($value, $this->flagReasons) ? $value : '';
        $this->note = $this->userFlag?->metadata['note'] ?? '';
        $this->showNoteField = $this->isNoteVisibleForReason($this->selectedReason);
        $this->setTitleText();
    }

    protected function deleteUserFlag(): void
    {
        Flag::where([
            'user_id' => auth()->id(),
            'markable_id' => $this->model->getKey(),
            'markable_type' => $this->model->getMorphClass(),
        ])->get()->each->delete();
    }

    public function store(): void
    {
        $metadata = [];

        $selectedReason = mb_trim($this->selectedReason);
        $noteText = mb_trim($this->note);

        if ($this->isNoteVisibleForReason($selectedReason) && mb_strlen($noteText) > 0) {
            $metadata = ['note' => $noteText];
        }

        // Just cancel the change if the form is unmodified
        if ($selectedReason === ($this->userFlag?->value ?? '') && $metadata === $this->userFlag?->metadata) {
            $this->cancel();
            return;
        }

        $event = $this->type === 'comment' ?
            LivewireEventEnum::CommentFlagged :
            LivewireEventEnum::PostFlagged;

        // Stop rendering while we are modifying data
        $this->formClosed = true;

        $this->deleteUserFlag();
        try {
            $this->userFlag = Flag::add($this->model, auth()->user(), $selectedReason, $metadata);
        } catch (InvalidMarkValueException $exception) {
            $this->logError($exception);
        }
        $this->updateFlagData();

        $this->dispatchEvent($event);
    }

    public function delete(): void
    {

        // Stop rendering while we are modifying data
        $this->formClosed = true;
        $event = $this->type === 'comment' ?
            LivewireEventEnum::CommentFlagDeleted :
            LivewireEventEnum::PostFlagDeleted;

        $this->deleteUserFlag();
        $this->userFlag = null;
        $this->updateFlagData();

        $this->dispatchEvent($event);
    }

    public function cancel(): void
    {
        $this->formClosed = true;

        $event = $this->type === 'comment' ?
            LivewireEventEnum::CommentFlagCancelled :
            LivewireEventEnum::PostFlagCancelled;

        $this->dispatchEvent($event);
    }

    private function getType(): string
    {
        $class = mb_strtolower($this->model::class);

        return str_replace(search: self::MODEL_PATH, replace: '', subject: $class);
    }

    private function setTitleText(): void
    {
        $flagText = 'Flag this ' . $this->type;

        $this->titleText = $this->userFlag ? trans('Remove or edit flag') : trans($flagText);
    }

    private function dispatchEvent(LivewireEventEnum $event): void
    {
        $this->dispatch($event->value, id: $this->model->id);
    }
}
