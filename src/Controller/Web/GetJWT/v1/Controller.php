<?php

namespace App\Controller\Web\GetJWT\v1;

use App\Controller\DTO\SuccessResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(private readonly Manager $manager) {}

    #[Route(path: '/api/v1/jwt', name: 'api_v1_get_jwt', methods: ['GET'])]
    public function __invoke(Request $request): SuccessResponse
    {
        return $this->manager->getJWT($request);
    }
}
