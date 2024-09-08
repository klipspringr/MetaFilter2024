<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

final class ContactMessageController extends BaseController
{
    public function create(): View
    {
        return view('contact.create');
    }
}
