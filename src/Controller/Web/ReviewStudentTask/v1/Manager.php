<?php

namespace App\Controller\Web\ReviewStudentTask\v1;

use App\Controller\DTO\SuccessResponse;
use App\Controller\Web\ReviewStudentTask\v1\Input\ReviewStudentTaskDTO;
use App\Domain\DTO\ReviewStudentTaskDTO as InternalReviewStudentTaskDTO;
use App\Domain\Enum\TaskStatus;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Service\StudentTaskService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(private readonly StudentTaskService $studentTaskService) {}

    public function reviewStudentTask(ReviewStudentTaskDTO $reviewStudentTaskDTO, int $studentTaskId): SuccessResponse
    {
        try {
            $reviewStudentTaskDTO = new InternalReviewStudentTaskDTO(
                TaskStatus::from($reviewStudentTaskDTO->taskStatus),
                $reviewStudentTaskDTO->skillIds,
            );
            $this->studentTaskService->reviewStudentTask($reviewStudentTaskDTO, $studentTaskId);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getDefaultMessage());
        }

        return new SuccessResponse();
    }
}
