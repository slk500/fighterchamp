<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\Tournament;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TicketFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $i) {
            $ticketNotAdult = new Ticket();
            $ticketNotAdult->setPrice(10);
            $ticketNotAdult->setUserType('fighter');
            $ticketNotAdult->setIsAdult(false);
            $ticketNotAdult->setInsurance(false);
            $ticketNotAdult->setTournament($this->getReference(Tournament::class . '_' . $i));

            $ticketAdult = clone $ticketNotAdult;
            $ticketAdult->setIsAdult(true);
            $ticketAdult->setPrice(20);
            $ticketAdult->setTournament($this->getReference(Tournament::class . '_' . $i));

            $manager->persist($ticketNotAdult);
            $manager->persist($ticketAdult);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TournamentFixtures::class,
        );
    }
}
