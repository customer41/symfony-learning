<?php

namespace App\Controller\Web\GetCourse\v1;

use App\Controller\Web\GetCourse\v1\Output\GetCourseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/course/{id}', name: 'api_v1_get_course', methods: ['GET'])]
    public function __invoke(int $id): GetCourseDTO
    {
        return $this->manager->getCourseById($id);
    }
}
