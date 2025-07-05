<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\View\Components\Icons\SvgIconRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearIconCache extends Command
{
    protected $signature = 'icon-cache:clear';
    protected $description = 'Clear cached Blade templates for SVG icons';

    public function handle()
    {
        File::deleteDirectory(SvgIconRegistry::getBladePath());
        $this->info('SVG icon cache cleared.');
    }
}
