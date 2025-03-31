<?php

namespace App\Controller\Web\CreateCourse\v1;

use App\Controller\Web\CreateCourse\v1\Input\CreateCourseDTO;
use App\Controller\Web\CreateCourse\v1\Output\CreatedCourseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[IsGranted('ROLE_MANAGER_CRUD_EDU_ENTITY')]
    #[Route(path: '/api/v1/course', name: 'api_v1_create_course', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateCourseDTO $createCourseDTO): CreatedCourseDTO
    {
        return $this->manager->create($createCourseDTO);
    }
}
