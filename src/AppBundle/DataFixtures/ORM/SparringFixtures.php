<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Club;
use AppBundle\Entity\Discipline;
use AppBundle\Entity\Sparring;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SparringFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1,10) as $i) {
            $sparring = new Sparring();

            $discipline = new Discipline(
                $this->faker->randomElement(['boks','MMA', 'kick-boxing'])
            );
            $manager->persist($discipline);

            $sparring->setName('Sparing ' . $i);
            $sparring->setStart(new \DateTime("+$i day"));
            $sparring->setCapacity($this->faker->numberBetween(5, 30));
            $sparring->addDiscipline($discipline);
            $manager->persist($sparring);

            $this->addReference(Sparring::class . '_' . $i, $sparring);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ClubFixtures::class,
        );
    }
}