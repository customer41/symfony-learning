<?php

namespace App\Controller\Web\GetStudentCourses\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

readonly class GetStudentCoursesDTO implements OutputDTOInterface
{
    public function __construct(
        /** @param GetStudentCourseDTO[] $courses */
        public array $courses,
    ) {
    }
}
