<?php

namespace App\Application\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: ExceptionEvent::class)]
class KernelExceptionEventListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            if ($exception->getPrevious() !== null) {
                $exception = $exception->getPrevious();
            }
        }

        if ($exception instanceof ValidationFailedException) {
            $event->setResponse($this->getValidationFailedResponse($exception));
        }

        if ($exception instanceof NotFoundHttpException) {
            $event->setResponse($this->getDefaultFailedResponse($exception, Response::HTTP_NOT_FOUND));
        }
    }

    private function getValidationFailedResponse(ValidationFailedException $exception): Response
    {
        $response = [];
        foreach ($exception->getViolations() as $violation) {
            $response[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    private function getDefaultFailedResponse(HttpExceptionInterface $exception, int $httpCode): Response
    {
        return new JsonResponse(['success' => false, 'error' => $exception->getMessage()], $httpCode);
    }
}
