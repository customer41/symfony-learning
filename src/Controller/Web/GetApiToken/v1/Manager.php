<?php

namespace App\Controller\Web\GetApiToken\v1;

use App\Application\Security\AuthService;
use App\Controller\DTO\SuccessResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Manager
{
    public function __construct(private readonly AuthService $authService) {}

    public function getToken(Request $request): SuccessResponse
    {
        $email = $request->getUser();
        $password = $request->getPassword();

        if (!$email || !$password) {
            throw new UnauthorizedHttpException('Basic realm="Test auth"', 'Unauthorized');
        }

        if (
            !$this->authService->isCredentialsValid($email, $password)
            || (($token = $this->authService->getToken($email)) === null)
        ) {
            throw new AccessDeniedHttpException('Access denied');
        }

        return new SuccessResponse(['token' => $token]);
    }
}
