<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Discipline;
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

            $discipline = new Discipline(
                $this->faker->randomElement(['boks','MMA', 'kick-boxing'])
            );
            $manager->persist($discipline);

            $tournament->setName('Turniej ' . $i);
            $tournament->setStart(new \DateTime("+$i day"));
            $tournament->addDiscipline($discipline);
            $tournament->setCapacity($this->faker->numberBetween(7, 12));

            $tournament->setPlace($this->getReference(Place::class . '_' . $i));


            $manager->persist($tournament);

            $this->addReference(Tournament::class . '_' . $i, $tournament);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PlaceFixtures::class,
        ];
    }
}
