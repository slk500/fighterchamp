<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\BaseFixture;
use AppBundle\Entity\Place;
use Doctrine\Common\Persistence\ObjectManager;

class PlaceFixtures extends BaseFixture
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $i) {
            $place = new Place();
            $place->setCapacity($this->faker->numberBetween(10, 100));
            $place->setCity($this->faker->city);
            $place->setName($this->faker->company);
            $place->setStreet($this->faker->streetName);
            $manager->persist($place);
            $this->addReference(Place::class . '_' . $i, $place);
        }
        $manager->flush();
    }
}
