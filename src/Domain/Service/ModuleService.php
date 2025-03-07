<?php

namespace App\Domain\Service;

use App\Domain\Entity\Module;
use App\Domain\Model\CreateModuleModel;
use App\Domain\Model\UpdateModuleModel;
use App\Infrastructure\Repository\CourseRepository;
use App\Infrastructure\Repository\ModuleRepository;
use Doctrine\ORM\EntityNotFoundException;

class ModuleService
{
    public function __construct(
        private readonly ModuleRepository $moduleRepository,
        private readonly CourseRepository $courseRepository,
    ) {
    }

    public function createModule(CreateModuleModel $createModuleModel): Module
    {
        $module = new Module();
        $module->setTitle($createModuleModel->title);

        if ($createModuleModel->existsCourse) {
            $course = $this->courseRepository->findById($createModuleModel->courseId);
            $module->setCourse($course);
        }

        $this->moduleRepository->create($module);

        return $module;
    }

    public function getModuleById(int $id): Module
    {
        $module = $this->moduleRepository->findById($id);

        if ($module === null) {
            throw new EntityNotFoundException();
        }

        return $module;
    }

    public function updateModule(UpdateModuleModel $updateModuleModel, int $moduleId): Module
    {
        $module = $this->moduleRepository->findById($moduleId);

        if ($module === null) {
            throw new EntityNotFoundException();
        }

        $module->setTitle($updateModuleModel->title);

        if ($updateModuleModel->existsCourse) {
            $course = $this->courseRepository->findById($updateModuleModel->courseId);
            $module->setCourse($course);
        } else {
            $module->setCourse(null);
        }

        $this->moduleRepository->update();

        return $module;
    }

    public function deleteModuleById(int $id): void
    {
        $module = $this->moduleRepository->findById($id);

        if ($module === null) {
            throw new EntityNotFoundException();
        }

        $this->moduleRepository->remove($module);
    }
}
