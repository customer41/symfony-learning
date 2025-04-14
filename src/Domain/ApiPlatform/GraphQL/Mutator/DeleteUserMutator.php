<?php

namespace App\Domain\ApiPlatform\GraphQL\Mutator;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Domain\Entity\User;
use App\Infrastructure\Repository\ManagerRepository;
use App\Infrastructure\Repository\StudentRepository;
use App\Infrastructure\Repository\UserRepository;

class DeleteUserMutator implements MutationResolverInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly StudentRepository $studentRepository,
        private readonly ManagerRepository $managerRepository,
    ) {
    }

    /**
     * @param User|null $item
     * @param array $context
     *
     * @return User|null
     */
    public function __invoke(?object $item, array $context): ?object
    {
        if (!$item instanceof User) {
            return null;
        }

        $this->userRepository->remove($item);

        if ($item->getStudent() !== null) {
            $this->studentRepository->remove($item->getStudent());
        }
        if ($item->getManager() !== null) {
            $this->managerRepository->remove($item->getManager());
        }

        return null;
    }
}
