<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Place;
use AppBundle\Entity\Tournament;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TournamentFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $i) {
            $tournament = new Tournament();


            $tournament->setName('Turniej ' . $i);
            $tournament->setStart(new \DateTime("+$i day"));
            $tournament->setDiscipline($this->faker->randomElement(['boks','MMA', 'kick-boxing']));
            $tournament->setCapacity($this->faker->numberBetween(7, 12));

            $tournament->setPlace($this->getReference(Place::class . '_' . $i));


            $manager->persist($tournament);

            $this->addReference(Tournament::class . '_' . $i, $tournament);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PlaceFixtures::class,
        );
    }
}
