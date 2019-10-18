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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserEventSubscriber implements EventSubscriberInterface
{
    private $mailer;

    private $router;

    public function __construct(AppMailer $mailer, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::USER_REGISTERED => [
                [
                    'sendActivationEmail',
                ],
            ],
        ];
    }

    public function sendActivationEmail(UserCreatedEvent $event)
    {
        $user = $event->getUser();
        $link = $this->router->generate(
            'user_validation',
            ['email' => $user->getEmail(),
                'hash'=> $user->getHash()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->mailer->sendEmail(
            $user->getEmail(),
            'Aktywacja Konta',
            "<a href='$link'>Kliknij tutaj aby aktywować konto</a>"
        );
    }
}
