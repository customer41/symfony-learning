<?php

namespace App\Controller\Web\DeleteCourse\v1;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Service\CourseService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(private readonly CourseService $courseService) {}

    public function deleteCourseById(int $id): void
    {
        try {
            $this->courseService->deleteCourseById($id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getDefaultMessage());
        }
    }
}
