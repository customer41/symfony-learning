<?php

namespace App\Application\EventListener;

use App\Controller\DTO\ErrorResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: ExceptionEvent::class)]
class KernelExceptionEventListener
{
    public function __construct(private readonly SerializerInterface $serializer) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            if ($exception->getPrevious() instanceof ValidationFailedException) {
                $validationFailedMessage = $this->getValidationFailedMessage();
                $validationErrors = $this->getValidationErrors($exception->getPrevious());
                $response = $this->getHttpResponse($validationFailedMessage, Response::HTTP_BAD_REQUEST, $validationErrors);
            } else {
                $response = $this->getHttpResponse($exception->getMessage(), $exception->getStatusCode());
            }

            $event->setResponse($response);
        }
    }

    private function getHttpResponse(string $message, int $code, array $details = []): Response
    {
        $errorResponse = new ErrorResponse($message, $details);
        $responseData = $this->serializer->serialize($errorResponse, JsonEncoder::FORMAT);

        return new Response($responseData, $code, ['Content-Type' => 'application/json']);
    }

    private function getValidationErrors(ValidationFailedException $exception): array
    {
        $errors = [];
        foreach ($exception->getViolations() as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }

    private function getValidationFailedMessage(): string
    {
        return 'Validation failed';
    }
}
