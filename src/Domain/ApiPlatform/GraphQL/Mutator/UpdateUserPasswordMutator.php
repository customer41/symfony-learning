<?php

namespace App\Domain\ApiPlatform\GraphQL\Mutator;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Domain\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserPasswordMutator implements MutationResolverInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

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

        $passwordHash = $this->passwordHasher->hashPassword($item, $context['args']['input']['password']);
        $item->setPassword($passwordHash);

        return $item;
    }
}
