<?php

declare(strict_types=1);

namespace App\Livewire\Tags;

use Illuminate\Contracts\View\View;

final class TagPostComponent extends BaseTagComponent
{
    public function render(): View
    {
        return view('livewire.tags.tag-post-component');
    }
}
