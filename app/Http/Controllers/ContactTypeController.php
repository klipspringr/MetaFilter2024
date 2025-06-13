<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactTypeRequest;
use App\Http\Requests\UpdateContactTypeRequest;
use App\Models\ContactType;
use Illuminate\Contracts\View\View;

final class ContactTypeController extends Controller
{
    public function index(): View
    {
        return view('contact-types.index');
    }

    public function show(ContactType $contactType): View
    {
        return view('contact-types.show', compact('contactType'));
    }

    public function create(): View
    {
        return view('contact-types.create');
    }

    public function store(StoreContactTypeRequest $request): void
    {
        //
    }

    public function edit(ContactType $contactType): View
    {
        return view('contact-types.edit', compact('contactType'));
    }

    public function update(UpdateContactTypeRequest $request, ContactType $contactType): void
    {
        //
    }

    public function delete(ContactType $contactType): void
    {
        //
    }
}
