<?php

namespace AppBundle\Form\EventListener;

use AppBundle\Entity\Discipline;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CreateDisciplineIfNotExist implements EventSubscriberInterface
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

        if (!array_key_exists('disciplines', $data)) {
            return;
        }

        $disciplinesIdsOrNames = $data['disciplines'];

        if (empty($disciplinesIdsOrNames)) {
            return;
        }

        //todo what if user will input integer?
        $result = [];
        foreach ($disciplinesIdsOrNames as $disciplineIdOrName) {
            if (is_numeric($disciplineIdOrName)) {
                $result []= $disciplineIdOrName;
                continue;
            }

            $discipline = new Discipline($disciplineIdOrName);
            $this->em->persist($discipline);
            $this->em->flush();

            $result []= $discipline->getId();
        }

        $data['disciplines'] = $result;
        $event->setData($data);
    }
}
