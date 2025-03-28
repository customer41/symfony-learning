<?php

namespace App\Controller\Web\GetJWT\v1;

use App\Application\Security\AuthService;
use App\Controller\DTO\SuccessResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Manager
{
    public function __construct(private readonly AuthService $authService) {}

    public function getJWT(Request $request): SuccessResponse
    {
        $email = $request->getUser();
        $password = $request->getPassword();

        if (!$email || !$password) {
            throw new UnauthorizedHttpException('Basic realm="Test getting jwt"', 'Unauthorized');
        }

        if (!$this->authService->isCredentialsValid($email, $password)) {
            throw new AccessDeniedHttpException('Access denied');
        }

        $jwt = $this->authService->getJWT($email);

        return new SuccessResponse(['jwt' => $jwt]);
    }
}
