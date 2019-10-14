<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Club;
use Doctrine\Common\Persistence\ObjectManager;

class ClubFixtures extends BaseFixture
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $i) {

            $club = new Club();
            $club->setName($this->faker->company);
            $club->setCity($this->faker->city);
            $club->setStreet($this->faker->streetAddress);
            $club->setWww($this->faker->url);

            $manager->persist($club);
            $manager->flush();

            $this->addReference(Club::class . '_' . $i, $club);
        }
    }
}
