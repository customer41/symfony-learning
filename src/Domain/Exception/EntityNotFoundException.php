<?php

namespace App\Domain\Exception;

class EntityNotFoundException extends \Exception
{
    private string $entityClass;

    public function __construct(
        string $entityClass,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        $this->entityClass = $entityClass;
        parent::__construct($message, $code, $previous);
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getDefaultMessage(): string
    {
        return substr(strrchr($this->entityClass, '\\'), 1) . ' not found';
    }
}
