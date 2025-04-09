<?php

namespace App\Domain\ApiPlatform\GraphQL\Mutator;

use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use App\Domain\Entity\Manager;
use App\Domain\Entity\Student;
use App\Domain\Entity\User;
use App\Domain\Enum\Gender;
use App\Domain\Enum\UserType;
use App\Infrastructure\Repository\ManagerRepository;
use App\Infrastructure\Repository\StudentRepository;
use Random\RandomException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserMutator implements MutationResolverInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly StudentRepository $studentRepository,
        private readonly ManagerRepository $managerRepository,
    ) {
    }

    /**
     * @param User|null $item
     * @param array $context
     *
     * @return User|null
     *
     * @throws RandomException
     * @throws \DateMalformedStringException
     */
    public function __invoke(?object $item, array $context): ?object
    {
        if (!$item instanceof User) {
            return null;
        }

        $args = $context['args']['input'];

        $item->setPassword($this->passwordHasher->hashPassword($item, $args['password']));
        $item->setApiToken(base64_encode(random_bytes(20)));

        if (!isset($args['createAs'])) {
            return $item;
        }

        $userType = UserType::tryFrom($args['createAs']);
        switch ($userType) {
            case UserType::Student:
                $student = new Student();
                $student->setBirthDate(isset($args['birthDate']) ? new \DateTime($args['birthDate']) : null);
                $student->setGender(Gender::tryFrom($args['gender'] ?? ''));
                $student->setPhone($args['phone'] ?? null);
                $student->setUser($item);
                $this->studentRepository->create($student);
                break;
            case UserType::Manager:
                $manager = new Manager();
                $manager->setPhone($args['phone'] ?? null);
                $manager->setUser($item);
                $this->managerRepository->create($manager);
                break;
            case UserType::Teacher:
                // TODO
        }

        return $item;
    }
}
