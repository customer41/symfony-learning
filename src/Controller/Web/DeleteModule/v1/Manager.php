<?php

namespace App\Controller\Web\DeleteModule\v1;

use App\Controller\DTO\SuccessResponse;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Service\ModuleService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(private readonly ModuleService $moduleService) {}

    public function deleteModuleById(int $id): SuccessResponse
    {
        try {
            $this->moduleService->deleteModuleById($id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getDefaultMessage());
        }

        return new SuccessResponse();
    }
}
