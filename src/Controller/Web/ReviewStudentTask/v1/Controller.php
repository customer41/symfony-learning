<?php

namespace App\Controller\Web\ReviewStudentTask\v1;

use App\Controller\DTO\SuccessResponse;
use App\Controller\Web\ReviewStudentTask\v1\Input\ReviewStudentTaskDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/review-task/{studentTaskId}', name: 'api_v1_review_task', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ReviewStudentTaskDTO $reviewStudentTaskDTO, int $studentTaskId): SuccessResponse
    {
        return $this->manager->reviewStudentTask($reviewStudentTaskDTO, $studentTaskId);
    }
}
