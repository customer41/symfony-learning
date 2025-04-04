<?php

namespace App\Controller\Web\UpdateModule\v1;

use App\Controller\Web\UpdateModule\v1\Input\UpdateModuleDTO;
use App\Controller\Web\UpdateModule\v1\Output\UpdatedModuleDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[IsGranted('ROLE_MANAGER_CRUD_EDU_ENTITY')]
    #[Route(path: '/api/v1/module/{id}', name: 'api_v1_update_module', methods: ['PUT'])]
    public function __invoke(#[MapRequestPayload] UpdateModuleDTO $updateModuleDTO, int $id): UpdatedModuleDTO
    {
        return $this->manager->update($updateModuleDTO, $id);
    }
}
