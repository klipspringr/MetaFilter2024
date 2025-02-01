<?php

declare(strict_types=1);

namespace App\Dtos;

final class MeFiMailDto {
    public function __construct(
        public string $subject,
        public string $body,
        public string $state,
        public int $sender_id,
        public int $recipient_id,
    ) {}
}
