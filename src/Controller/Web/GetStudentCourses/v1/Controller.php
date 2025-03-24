<?php

namespace App\Controller\Web\GetStudentCourses\v1;

use App\Controller\Web\GetStudentCourses\v1\Output\GetStudentCoursesDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/student-courses', name: 'api_v1_get_student_courses', methods: ['GET'])]
    public function __invoke(): GetStudentCoursesDTO
    {
        return $this->manager->getCourses();
    }
}
