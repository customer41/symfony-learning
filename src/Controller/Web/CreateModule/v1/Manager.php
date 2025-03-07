<?php

namespace App\Controller\Web\CreateModule\v1;

use App\Controller\Web\CreateModule\v1\Input\CreateModuleDTO;
use App\Controller\Web\CreateModule\v1\Output\CreatedModuleDTO;
use App\Domain\Factory\ModelFactory;
use App\Domain\Model\CreateModuleModel;
use App\Domain\Service\CourseService;
use App\Domain\Service\ModuleService;

class Manager
{
    public function __construct(
        /** @var ModelFactory<CreateModuleModel> */
        private readonly ModelFactory $modelFactory,
        private readonly ModuleService $moduleService,
        private readonly CourseService $courseService,
    ) {
    }

    public function create(CreateModuleDTO $createModuleDTO): CreatedModuleDTO
    {
        $isExistsCourse = $createModuleDTO->courseId !== null && $this->courseService->isExistsCourseById($createModuleDTO->courseId);

        $createModuleModel = $this->modelFactory->makeModel(
            CreateModuleModel::class,
            $createModuleDTO->title,
            $createModuleDTO->courseId,
            $isExistsCourse,
        );

        $module = $this->moduleService->createModule($createModuleModel);

        return new CreatedModuleDTO(
            $module->getId(),
            $module->getTitle(),
            $module->getCourse()?->getId(),
            $module->getCreatedAt()->format('Y-m-d H:i:s'),
            $module->getUpdatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
