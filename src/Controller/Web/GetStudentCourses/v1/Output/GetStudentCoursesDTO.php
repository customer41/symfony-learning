<?php

namespace App\Controller\Web\GetStudentCourses\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

readonly class GetStudentCoursesDTO implements OutputDTOInterface
{
    /** @param GetStudentCourseDTO[] $courses */
    public function __construct(
        public array $courses,
    ) {
    }
}
