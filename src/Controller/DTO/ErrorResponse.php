<?php

namespace App\Controller\DTO;

readonly class ErrorResponse
{
    private bool $success;

    public function __construct(
        private string $message,
        private array $details = [],
    ) {
        $this->success = false;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
