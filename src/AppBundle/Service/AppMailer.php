<?php

namespace AppBundle\Service;

use Swift_Mailer;

class AppMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(Swift_Mailer $_mailer)
    {
        $this->mailer = $_mailer;
    }

    public function sendEmail($to, $subject, $body): int
    {
        $message = (new \Swift_Message())
                ->setSubject($subject)
                ->setFrom('fighterchamp@fighterchamp.pl', 'FighterChamp')
                ->setTo($to)
                ->setBody($body, 'text/html');
        ;

        return $this->mailer->send($message);
    }
}
