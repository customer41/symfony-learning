<?php

namespace App\Application\EventListener;

use App\Controller\DTO\OutputDTOInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: ViewEvent::class)]
class KernelViewExceptionListener
{
    public function __construct(private readonly SerializerInterface $serializer) {}

    public function onKernelView(ViewEvent $event): void
    {
        $dto = $event->getControllerResult();

        if ($dto instanceof OutputDTOInterface) {
            $event->setResponse($this->getDTOResponse($dto));
        }
    }

    private function getDTOResponse(OutputDTOInterface $dto): Response
    {
        $serializedData = $this->serializer->serialize($dto, 'json');

        return new JsonResponse($serializedData, json: true);
    }
}
