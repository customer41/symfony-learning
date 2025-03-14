<?php

namespace App\Controller\DTO;

readonly class SuccessResponse
{
    private bool $success;

    public function __construct(
        private OutputDTOInterface|array $data = [],
    ) {
        $this->success = true;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getData(): OutputDTOInterface|array
    {
        return $this->data;
    }
}
