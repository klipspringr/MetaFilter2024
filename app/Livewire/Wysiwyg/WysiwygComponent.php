<?php

declare(strict_types=1);

namespace App\Livewire\Wysiwyg;

use App\Enums\LivewireEventEnum;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Modelable;
use Livewire\Component;

final class WysiwygComponent extends Component
{
    #[Modelable]
    public string $content = '';

    #[Locked]
    public string $editorId;

    #[Locked]
    public string $label;

    #[Locked]
    public string $name;

    public function mount(string $editorId, string $content = '', string $label = '', string $name = ''): void
    {
        $this->editorId = $editorId;
        $this->content = $content;
        $this->label = $label;
        $this->name = $name ?: $editorId;
    }

    public function updatedContent($value): void
    {
        $this->dispatch(LivewireEventEnum::EditorUpdated->value, editorId: $this->editorId, content: $value);
    }

    public function render(): View
    {
        return view('livewire.wysiwyg.wysiwyg-component', [
            'editorConfig' => config('ckeditor'),
        ]);
    }
}
