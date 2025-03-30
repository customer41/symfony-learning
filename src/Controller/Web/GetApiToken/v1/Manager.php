<?php

namespace App\Controller\Web\GetApiToken\v1;

use App\Application\Security\AuthService;
use App\Controller\DTO\SuccessResponse;
use App\Domain\Exception\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Manager
{
    public function __construct(private readonly AuthService $authService) {}

    public function getToken(Request $request): SuccessResponse
    {
        $email = $request->getUser();
        $password = $request->getPassword();

        if (!$email || !$password) {
            throw new UnauthorizedHttpException('Basic realm="Test getting simple token"', 'Unauthorized');
        }

        if (!$this->authService->isCredentialsValid($email, $password)) {
            throw new AccessDeniedHttpException('Access denied');
        }

        try {
            $token = $this->authService->getToken($email);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getDefaultMessage());
        }

        return new SuccessResponse(['token' => $token]);
    }
}
