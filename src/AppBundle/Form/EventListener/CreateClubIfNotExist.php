<?php

namespace AppBundle\Form\EventListener;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CreateClubIfNotExist implements EventSubscriberInterface
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

        $clubId = $data['club'];

        if (!$clubId) {
            return;
        }

        if ($this->em->getRepository(Club::class)->find($clubId)) {
            return;
        }

        $clubNameRaw = $data['club'];
        $clubName = trim($clubNameRaw);

        $club = new Club();
        $club->setName($clubName);
        $this->em->persist($club);
        $this->em->flush();

        $data['club'] = $club->getId();
        $event->setData($data);
    }
}
