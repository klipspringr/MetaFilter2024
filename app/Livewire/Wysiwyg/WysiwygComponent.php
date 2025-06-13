<?php

declare(strict_types=1);

namespace App\Livewire\Wysiwyg;

use Illuminate\Contracts\View\View;
use Livewire\Component;

final class WysiwygComponent extends Component
{
    public string $content = '';
    public string $editorId;
    public string $label;
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
        $this->dispatch('editorUpdated', editorId: $this->editorId, content: $value);
    }

    public function render(): View
    {
        return view('livewire.wysiwyg.wysiwyg-component', [
            'editorConfig' => config('ckeditor'),
        ]);
    }
}
