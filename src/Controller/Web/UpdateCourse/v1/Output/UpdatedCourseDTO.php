<?php

namespace App\Controller\Web\UpdateCourse\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

readonly class UpdatedCourseDTO implements OutputDTOInterface
{
    public function __construct(
        public int $id,
        public string $title,
        public string $status,
        public ?string $manager,
        public ?string $startDate,
        public ?string $endDate,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }
}
