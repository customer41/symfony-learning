<?php

namespace App\Controller\Web\UpdateModule\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

readonly class UpdatedModuleDTO implements OutputDTOInterface
{
    public function __construct(
        public int $id,
        public string $title,
        public ?int $courseId,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }
}
