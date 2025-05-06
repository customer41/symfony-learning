<?php

namespace App\Controller\Web\GetOneCourseManyStudentsStats\v1;

use App\Controller\Web\GetOneCourseManyStudentsStats\v1\Input\StudentsCourseDTO;
use App\Controller\Web\GetOneCourseManyStudentsStats\v1\Output\OneCourseManyStudentsDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/stats/course/{courseId}', name: 'api_v1_get_stats_by_course', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] StudentsCourseDTO $studentsCourseDTO, int $courseId): OneCourseManyStudentsDTO
    {
        return $this->manager->getOneCourseManyStudentsStats($studentsCourseDTO, $courseId);
    }
}
