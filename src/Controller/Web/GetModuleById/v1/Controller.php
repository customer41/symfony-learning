<?php

namespace App\Controller\Web\GetModuleById\v1;

use App\Controller\Web\GetModuleById\v1\Output\GetModuleDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[IsGranted('ROLE_MANAGER_CRUD_EDU_ENTITY')]
    #[Route(path: '/api/v1/module/{id}', name: 'api_v1_get_module', methods: ['GET'])]
    public function __invoke(int $id): GetModuleDTO
    {
        return $this->manager->getModuleById($id);
    }
}
