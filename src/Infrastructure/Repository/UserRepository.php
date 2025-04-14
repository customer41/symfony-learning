<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Enum\UserType;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    public function create(User $user): int
    {
        return $this->store($user);
    }

    public function findById(int $id): ?User
    {
        return $this->repositoryApi->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repositoryApi->findOneBy(['email' => $email]);
    }

    public function findByToken(string $token): ?User
    {
        return $this->repositoryApi->findOneBy(['apiToken' => $token]);
    }

    public function findByRefreshToken(string $token): ?User
    {
        return $this->repositoryApi->findOneBy(['refreshToken' => $token]);
    }

    /**
     * @return User[]
     */
    public function findActive(?UserType $userType = null): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.isActive = true');

        switch ($userType) {
            case UserType::Student:
                $queryBuilder->join('u.student', 's');
                break;
            case UserType::Manager:
                $queryBuilder->join('u.manager', 'm');
                break;
            case UserType::Teacher:
                // TODO
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function updateToken(User $user): string
    {
        $token = base64_encode(random_bytes(20));

        $user->setApiToken($token);
        $this->flush();

        return $token;
    }

    public function clearRefreshToken(User $user): void
    {
        $user->setRefreshToken(null);
        $this->flush();
    }

    public function updateRefreshToken(User $user): string
    {
        $token = base64_encode(random_bytes(20));

        $user->setRefreshToken($token);
        $this->flush();

        return $token;
    }

    public function remove(User $user): void
    {
        $user->setDeletedAt();
        $this->flush();
    }
}
