<?php

namespace App\Controller\Web\UpdateCourse\v1;

use App\Controller\Web\UpdateCourse\v1\Input\UpdateCourseDTO;
use App\Controller\Web\UpdateCourse\v1\Output\UpdatedCourseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/course/{id}', name: 'api_v1_update_course', methods: ['PUT'])]
    public function __invoke(#[MapRequestPayload] UpdateCourseDTO $updateCourseDTO, int $id): UpdatedCourseDTO
    {
        return $this->manager->update($updateCourseDTO, $id);
    }
}
