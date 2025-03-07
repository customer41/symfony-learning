<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Module;
use Doctrine\ORM\EntityManagerInterface;

class ModuleRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Module::class);
    }

    public function create(Module $module): int
    {
        return $this->store($module);
    }

    public function findById(int $id): ?Module
    {
        return $this->repositoryApi->find($id);
    }

    public function update(): void
    {
        $this->flush();
    }

    public function remove(Module $module): void
    {
        $module->setDeletedAt();
        $this->flush();
    }
}
