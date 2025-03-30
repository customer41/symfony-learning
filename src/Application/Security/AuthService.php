<?php

namespace App\Application\Security;

use App\Domain\Entity\User;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Service\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTEncoderInterface $jwtEncoder,
        private readonly int $tokenTTL,
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

    /**
     * @throws EntityNotFoundException
     */
    public function getToken(string $email): string
    {
        return $this->userService->updateUserToken($email);
    }

    /**
     * @throws EntityNotFoundException
     * @throws JWTEncodeFailureException
     */
    public function getJWT(string $email): string
    {
        $user = $this->userService->findUserByEmail($email);
        if ($user === null) {
            throw new EntityNotFoundException(User::class);
        }

        $refreshToken = $this->userService->updateRefreshToken($user);
        $jwtData = [
            'email' => $email,
            'roles' => $user->getRoles() ?? [],
            'refresh_token' => $refreshToken,
            'exp' => time() + $this->tokenTTL,
        ];

        return $this->jwtEncoder->encode($jwtData);
    }
}
