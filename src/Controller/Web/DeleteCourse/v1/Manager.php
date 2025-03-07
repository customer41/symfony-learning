<?php

namespace App\Controller\Web\DeleteCourse\v1;

use App\Domain\Service\CourseService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(private readonly CourseService $courseService) {}

    public function deleteCourseById(int $id): void
    {
        try {
            $this->courseService->deleteCourseById($id);
        } catch (EntityNotFoundException) {
            throw new NotFoundHttpException('Course not found');
        }
    }
}
