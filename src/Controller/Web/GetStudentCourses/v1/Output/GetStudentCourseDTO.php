<?php

namespace App\Controller\Web\GetStudentCourses\v1\Output;

readonly class GetStudentCourseDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $status,
        public ?string $manager,
        public ?string $startDate,
        public ?string $endDate,
    ) {
    }
}
