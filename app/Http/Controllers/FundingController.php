<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

final class FundingController extends BaseController
{
    public function index(): View
    {
        return view('funding.index', [
            'title' => trans('Funding'),
        ]);
    }
}
