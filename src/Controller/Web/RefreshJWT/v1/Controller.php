<?php

namespace App\Controller\Web\RefreshJWT\v1;

use App\Controller\DTO\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/refresh-jwt', name: 'api_v1_refresh_jwt', methods: ['GET'])]
    public function __invoke(): SuccessResponse
    {
        return $this->manager->refreshJWT();
    }
}
