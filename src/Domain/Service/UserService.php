<?php

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\CreateUserModel;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function createUser(CreateUserModel $createUserModel): User
    {
        $user = new User();
        $user->setEmail($createUserModel->email);
        $user->setFirstName($createUserModel->firstName);
        $user->setLastName($createUserModel->lastName);
        $user->setPassword($this->passwordHasher->hashPassword($user, $createUserModel->password));
        $user->setIsActive($createUserModel->isActive);
        $user->setRoles($createUserModel->roles);
        $user->setApiToken(base64_encode(random_bytes(20)));
        $this->userRepository->create($user);

        return $user;
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findUserByToken(string $token): ?User
    {
        return $this->userRepository->findByToken($token);
    }

    public function findUserByRefreshToken(string $token): ?User
    {
        return $this->userRepository->findByRefreshToken($token);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateUserToken(string $email): string
    {
        $user = $this->findUserByEmail($email);
        if ($user === null) {
            throw new EntityNotFoundException(User::class);
        }

        return $this->userRepository->updateToken($user);
    }

    public function clearRefreshToken(User $user): void
    {
        $this->userRepository->clearRefreshToken($user);
    }

    public function updateRefreshToken(User $user): string
    {
        return $this->userRepository->updateRefreshToken($user);
    }
}
