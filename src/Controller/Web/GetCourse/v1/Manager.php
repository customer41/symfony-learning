<?php

namespace App\Controller\Web\GetCourse\v1;

use App\Controller\Web\GetCourse\v1\Output\GetCourseDTO;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Service\CourseService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(private readonly CourseService $courseService) {}

    public function getCourseById(int $id): GetCourseDTO
    {
        try {
            $course = $this->courseService->getCourseById($id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getDefaultMessage());
        }

        return new GetCourseDTO(
            $course->getId(),
            $course->getTitle(),
            $course->getStatus()->value,
            $course->getManager(),
            $course->getStartDate()?->format('Y-m-d'),
            $course->getEndDate()?->format('Y-m-d'),
            $course->getCreatedAt()->format('Y-m-d H:i:s'),
            $course->getUpdatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
