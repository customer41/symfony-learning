<?php

namespace App\Domain\DTO;

use App\Domain\Enum\EducationItem;

readonly class StudentCourseDTO
{
    public function __construct(
        public int $courseId,
        public int $studentId,
        public EducationItem $aggregateBy,
    ) {
    }
}
