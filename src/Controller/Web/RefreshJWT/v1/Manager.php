<?php

namespace App\Controller\Web\RefreshJWT\v1;

use App\Application\Security\AuthService;
use App\Controller\DTO\SuccessResponse;
use App\Domain\Entity\User;
use App\Domain\Service\UserService;
use Symfony\Bundle\SecurityBundle\Security;

class Manager
{
    public function __construct(
        private readonly Security $security,
        private readonly AuthService $authService,
        private readonly UserService $userService,
    ) {
    }

    public function refreshJWT(): SuccessResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $this->userService->clearRefreshToken($user);
        $jwt = $this->authService->getJWT($user->getEmail());

        return new SuccessResponse(['jwt' => $jwt]);
    }
}
