<?php

namespace App\Controller\Web\GetModule\v1;

use App\Controller\Web\GetModule\v1\Output\GetModuleDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/module/{id}', name: 'api_v1_get_module', methods: ['GET'])]
    public function __invoke(int $id): GetModuleDTO
    {
        return $this->manager->getModuleById($id);
    }
}
