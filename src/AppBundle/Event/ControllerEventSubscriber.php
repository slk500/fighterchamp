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

        $json = $this->serializer->normalize($value);

        $response = new JsonResponse(['data' => $json]);

        $event->setResponse($response);
    }
}
