<?php

namespace App\Controller\Web\DeleteCourse\v1;

use App\Controller\DTO\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[IsGranted('ROLE_MANAGER_CRUD_EDU_ENTITY')]
    #[Route(path: '/api/v1/course/{id}', name: 'api_v1_delete_course', methods: ['DELETE'])]
    public function __invoke(int $id): SuccessResponse
    {
        return $this->manager->deleteCourseById($id);
    }
}
