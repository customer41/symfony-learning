<?php

namespace App\Domain\DTO;

readonly class StudentsCourseDTO
{
    public function __construct(
        public int $courseId,
        public \DatePeriod $period,
        public int $sortOrder,
    ) {
    }
}
