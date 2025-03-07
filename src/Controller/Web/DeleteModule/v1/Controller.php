<?php

namespace App\Controller\Web\DeleteModule\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/module/{id}', name: 'api_v1_delete_module', methods: ['DELETE'])]
    public function __invoke(int $id): Response
    {
        $this->manager->deleteModuleById($id);

        return $this->json(['success' => true]);
    }
}
