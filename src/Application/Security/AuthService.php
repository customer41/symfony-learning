<?php

namespace App\Application\Security;

use App\Domain\Service\UserService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function isCredentialsValid(string $email, string $password): bool
    {
        $user = $this->userService->findUserByEmail($email);
        if ($user === null) {
            return false;
        }

        return $this->passwordHasher->isPasswordValid($user, $password);
    }

    public function getToken(string $email): ?string
    {
        return $this->userService->updateUserToken($email);
    }
}
