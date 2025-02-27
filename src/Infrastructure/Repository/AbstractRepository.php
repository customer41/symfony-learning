<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;

/**
 * @template T
 */
abstract class AbstractRepository
{
    protected EntityRepository $repositoryApi;

    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        string $entityClassName,
    ) {
        $this->repositoryApi = $this->entityManager->getRepository($entityClassName);
    }

    protected function flush(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @param T $entity
     */
    protected function store(EntityInterface $entity): int
    {
        $this->entityManager->persist($entity);
        $this->flush();

        return $entity->getId();
    }

    /**
     * @param T $entity
     *
     * @throws ORMException
     */
    public function refresh(EntityInterface $entity): void
    {
        $this->entityManager->refresh($entity);
    }
}
