<?php

namespace App\Controller\Web\GetModule\v1;

use App\Controller\Web\GetModule\v1\Output\GetModuleDTO;
use App\Domain\Service\ModuleService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(private readonly ModuleService $moduleService) {}

    public function getModuleById(int $id): GetModuleDTO
    {
        try {
            $module = $this->moduleService->getModuleById($id);
        } catch (EntityNotFoundException) {
            throw new NotFoundHttpException('Module not found');
        }

        return new GetModuleDTO(
            $module->getId(),
            $module->getTitle(),
            $module->getCourse()?->getId(),
            $module->getCreatedAt()->format('Y-m-d H:i:s'),
            $module->getUpdatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
