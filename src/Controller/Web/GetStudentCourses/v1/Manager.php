<?php

namespace App\Controller\Web\GetStudentCourses\v1;

use App\Controller\Web\GetStudentCourses\v1\Output\GetStudentCourseDTO;
use App\Controller\Web\GetStudentCourses\v1\Output\GetStudentCoursesDTO;
use App\Domain\Service\CourseService;

class Manager
{
    public function __construct(private readonly CourseService $courseService) {}

    public function getCourses(): GetStudentCoursesDTO
    {
        $courses = [];
        foreach ($this->courseService->getCurrentStudentCourses() as $course) {
            $courses[] = new GetStudentCourseDTO(
                $course->getId(),
                $course->getTitle(),
                $course->getStatus()->value,
                $course->getManager(),
                $course->getStartDate()?->format('Y-m-d'),
                $course->getEndDate()?->format('Y-m-d'),
            );
        }

        return new GetStudentCoursesDTO($courses);
    }
}
