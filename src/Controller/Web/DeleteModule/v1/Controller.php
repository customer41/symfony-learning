<?php

namespace App\Controller\Web\DeleteModule\v1;

use App\Controller\DTO\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/module/{id}', name: 'api_v1_delete_module', methods: ['DELETE'])]
    public function __invoke(int $id): SuccessResponse
    {
        return $this->manager->deleteModuleById($id);
    }
}
