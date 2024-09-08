<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\Contact\SendContactMessageRequest;
use App\Repositories\ContactMessageRepositoryInterface;
use App\Traits\LoggingTrait;
use Exception;

final class ContactMessageService
{
    use LoggingTrait;

    public function __construct(protected ContactMessageRepositoryInterface $contactMessageRepository) {}

    public function store(SendContactMessageRequest $request): bool
    {
        try {
            $this->contactMessageRepository->create($request->validated());

            return true;
        } catch (Exception $exception) {
            $this->logError($exception);

            return false;
        }
    }
}
