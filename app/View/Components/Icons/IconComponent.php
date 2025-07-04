<?php

declare(strict_types=1);

namespace App\View\Components\Icons;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class IconComponent extends Component
{
    public string $altText = '';
    public string $class = '';
    public string $filename = '';
    public string $titleText = '';

    public function __construct(
        string $filename,
        string $altText = '',
        string $titleText = '',
        string $class = '',
    ) {
        $this->filename = $filename;
        $this->altText = $altText;
        $this->titleText = $titleText;
        $this->class = $class;
    }

    public function render(): View
    {
        return view('components.icons.icon-component', [
            'filename' => $this->filename,
            'class' => $this->class,
            'altText' => $this->altText,
            'titleText' => $this->titleText,
        ]);
    }
}
