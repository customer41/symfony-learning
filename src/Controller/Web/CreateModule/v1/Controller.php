<?php

namespace App\Controller\Web\CreateModule\v1;

use App\Controller\Web\CreateModule\v1\Input\CreateModuleDTO;
use App\Controller\Web\CreateModule\v1\Output\CreatedModuleDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/module', name: 'api_v1_create_module', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateModuleDTO $createModuleDTO): CreatedModuleDTO
    {
        return $this->manager->create($createModuleDTO);
    }
}
