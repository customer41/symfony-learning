<?php

namespace App\Controller\Web\DeleteCourse\v1;

use App\Controller\DTO\SuccessResponse;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Service\CourseService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(private readonly CourseService $courseService) {}

    public function deleteCourseById(int $id): SuccessResponse
    {
        try {
            $this->courseService->deleteCourseById($id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getDefaultMessage());
        }

        return new SuccessResponse();
    }
}
