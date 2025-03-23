<?php

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Infrastructure\Repository\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function updateUserToken(string $email): ?string
    {
        $user = $this->findUserByEmail($email);
        if ($user === null) {
            return null;
        }

        return $this->userRepository->updateToken($user);
    }
}
