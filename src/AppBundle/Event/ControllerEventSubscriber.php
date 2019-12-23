<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ControllerEventSubscriber implements EventSubscriberInterface
{
    private $serializer;

    public function __construct(NormalizerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


    public static function getSubscribedEvents()
    {
        return [
            Events::KERNEL_VIEW => [
                [
                    'sendResponse',
                ],
            ],
        ];
    }

    public function sendResponse(GetResponseForControllerResultEvent $event)
    {
        $value = $event->getControllerResult();

        $method = $event->getRequest()->getMethod(); //todo shitfix -> should be based on controller not request

        $statusCode = $this->getStatusCode($method);

        $json = $this->serializer->normalize($value);

        $response = new JsonResponse(['data' => $json], $statusCode);

        $event->setResponse($response);
    }

    public function getStatusCode(string $method): int
    {
        if ('GET' === $method) {
            return 200;
        }
        if ('POST' === $method) {
            return 201;
        }
        if ('DELETE' === $method) {
            return 204;
        }

        return 200;
    }
}
