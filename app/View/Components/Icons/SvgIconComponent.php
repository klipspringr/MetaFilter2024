<?php

declare(strict_types=1);

namespace App\View\Components\Icons;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class SvgIconComponent extends Component
{
    private SvgIconRegistry $registry;
    public string $altText = '';
    public string $class = '';
    public string $filename = '';
    public string $titleText = '';

    public function __construct(
        SvgIconRegistry $registry,
        string $filename,
        string $class = '',
        string $label = '',
        string $title = '',
    ) {
        $this->registry = $registry;
        $this->filename = $filename;
        $this->class = $class;
        $this->label = $label;
        $this->title = $title;
    }

    public function render(): View
    {
        $firstRender = $this->registry->isFirstRender($this->filename);
        $viewName = $this->registry->getViewName($this->filename);

        return view($viewName, [
            'class' => $this->class,
            'firstRender' => $firstRender,
            'label' => $this->label,
            'title' => $this->title,
        ]);
    }
}
