<?php

namespace App\Controller\Web\UpdateModule\v1;

use App\Controller\Web\UpdateModule\v1\Input\UpdateModuleDTO;
use App\Controller\Web\UpdateModule\v1\Output\UpdatedModuleDTO;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Factory\ModelFactory;
use App\Domain\Model\UpdateModuleModel;
use App\Domain\Service\CourseService;
use App\Domain\Service\ModuleService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(
        /** @var ModelFactory<UpdateModuleModel> */
        private readonly ModelFactory $modelFactory,
        private readonly ModuleService $moduleService,
        private readonly CourseService $courseService,
    ) {
    }

    public function update(UpdateModuleDTO $updateModuleDTO, int $moduleId): UpdatedModuleDTO
    {
        $isExistsCourse = $updateModuleDTO->courseId !== null && $this->courseService->isExistsCourseById($updateModuleDTO->courseId);

        $updateModuleModel = $this->modelFactory->makeModel(
            UpdateModuleModel::class,
            $updateModuleDTO->title,
            $updateModuleDTO->courseId,
            $isExistsCourse,
        );

        try {
            $module = $this->moduleService->updateModule($updateModuleModel, $moduleId);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getDefaultMessage());
        }

        return new UpdatedModuleDTO(
            $module->getId(),
            $module->getTitle(),
            $module->getCourse()?->getId(),
            $module->getCreatedAt()->format('Y-m-d H:i:s'),
            $module->getUpdatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
