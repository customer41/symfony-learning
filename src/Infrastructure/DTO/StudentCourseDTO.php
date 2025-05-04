<?php

namespace App\Infrastructure\DTO;

use App\Domain\Enum\EducationItem;

readonly class StudentCourseDTO
{
    public function __construct(
        public int $studentId,
        public int $courseId,
        public ?EducationItem $aggregateBy,
    ) {
    }
}
