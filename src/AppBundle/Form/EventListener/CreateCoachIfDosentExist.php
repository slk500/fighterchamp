<?php

namespace AppBundle\Form\EventListener;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CreateCoachIfDosentExist implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SUBMIT => 'preSubmitData'];
    }

    public function preSubmitData(FormEvent $event)
    {
        $data = $event->getData();

        if (!$data) {
            return;
        }

        $userId = $data['users'];


        if ($this->em->getRepository(User::class)->find($userId)) {
            return;
        }

        if (!$userId) {
            return;
        }

        $clubName = $userId;

        $user = new User();
        $user->setName($clubName);
        $user->setSurname('');
        $user->setHash('');
        $this->em->persist($user);
        $this->em->flush();

        $data['users'] = $user->getId();
        $event->setData($data);
    }
}
