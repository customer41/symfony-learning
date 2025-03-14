<?php

namespace App\Application\EventListener;

use App\Controller\DTO\OutputDTOInterface;
use App\Controller\DTO\SuccessResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: ViewEvent::class)]
class KernelViewExceptionListener
{
    public function __construct(private readonly SerializerInterface $serializer) {}

    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if ($result instanceof OutputDTOInterface) {
            $result = new SuccessResponse($result);
        }

        $event->setResponse($this->getHttpResponse($result));
    }

    private function getHttpResponse(mixed $successResponse): Response
    {
        $responseData = $this->serializer->serialize($successResponse, JsonEncoder::FORMAT);

        return new Response($responseData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
