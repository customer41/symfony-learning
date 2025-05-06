<?php

namespace App\Controller\Web\GetOneCourseOneStudentStats\v1;

use App\Controller\Web\GetOneCourseOneStudentStats\v1\Input\StudentCourseDTO;
use App\Controller\Web\GetOneCourseOneStudentStats\v1\Output\OneCourseOneStudentDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/stats/student-course', name: 'api_v1_get_stats_by_student_course', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] StudentCourseDTO $studentCourseDTO): OneCourseOneStudentDTO
    {
        return $this->manager->getOneCourseOneStudentStats($studentCourseDTO);
    }
}
