<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 4/13/18
 * Time: 1:20 PM
 */

namespace AppBundle\Event;

use AppBundle\Service\AppMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

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
